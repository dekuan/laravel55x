<?php

namespace App\Http\Lib;

use dekuan\delib\CLib;



/**
 *	Class SignLib
 *	@package App\Http\Lib
 */
class SignLib
{
	static function createCommonSign( $arrData )
	{
		$sRet	= '';
		$sKey	= '';
		$sSrc	= '';

		if ( CLib::IsArrayWithKeys( $arrData ) )
		{
			foreach ( $arrData as $nIndex => $vValue )
			{
				$arrData[ $nIndex ] = trim( strval( $vValue ) );
			}

			$sKey	= config( 'xc.sign.common', date( "Y-m-d" ) );
			$sSrc	= sprintf( "common-%s-%s", $sKey, implode( '|||', $arrData ) );
			$sRet	= md5( $sSrc );
		}

		return $sRet;
	}

	static function isValidCommonSign( $arrData, $sSign )
	{
		$bRet		= false;
		$sCalcSign	= self::createCommonSign( $arrData );
		$bRet		= CLib::IsCaseSameString( $sCalcSign, $sSign );

		//	...
		return $bRet;
	}

	
}