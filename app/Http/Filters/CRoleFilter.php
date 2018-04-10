<?php

namespace App\Http\Filters;

use Illuminate\Support\Facades\Input;
use App\Http\Constants\CConstantX;

use App\Http\Lib\UserLib;

use dekuan\vdata\CConst;
use dekuan\delib\CLib;
use dekuan\delib\CMIdLib;


/**
 *	Class CRoleFilter
 *	@package App\Http\Filters
 */
class CRoleFilter
{
	public function __construct()
	{
	}
	public function __destruct()
	{
	}


	static function isValidRole( $nRole )
	{
		if ( ! is_numeric( $nRole ) || $nRole <= 0 )
		{
			return false;
		}

		//	...
		$bRet			= false;
		$nRole			= intval( $nRole );
		$nUsrRole		= CConstantX::ROLE_UNKNOWN;

		//	...
		$arrResult	=
		[
			CConstantX::ROLE_ANYONE	=>
			[
				function()
				{
					return true;
				}
			],
			CConstantX::ROLE_MEMBER	=>
			[
				function()
				{
					return UserLib::isLoggedIn();
				}
			],
			CConstantX::ROLE_OPERATOR	=>
			[
				function() use ( $nUsrRole )
				{
					return (
						UserLib::isLoggedIn() &&
						CConstantX::ROLE_OPERATOR == $nUsrRole
					);
				}
			],
			CConstantX::ROLE_ADMIN	=>
			[
				function() use ( $nUsrRole )
				{
					return (
						UserLib::isLoggedIn() &&
						CConstantX::ROLE_ADMIN == $nUsrRole
					);
				}
			],
			CConstantX::ROLE_SUPER_ADMIN	=>
			[
				function() use ( $nUsrRole )
				{
					return (
						UserLib::isLoggedIn() &&
						CConstantX::ROLE_SUPER_ADMIN == $nUsrRole
					);
				}
			]
		];

		//
		//	...
		//
		if ( array_key_exists( $nRole, $arrResult ) )
		{
			$bRet	= boolval( $arrResult[ $nRole ][ 0 ]() );
		}

		return $bRet;
	}


	//
	//	get parameter from URL/Route
	//
	static function getParameter( $sName )
	{
		if ( ! is_string( $sName ) )
		{
			return null;
		}

		//	...
		$vRet	= Input::get( $sName, null );
		if ( ! CMIdLib::isValidMId( $vRet ) )
		{
			$vRet = request()->route($sName );
		}

		return $vRet;
	}



}