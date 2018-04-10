<?php

namespace App\Http\Lib;

use App\Http\Constants\CConstantX;
use dekuan\delib\CMIdLib;
use Illuminate\Support\Facades\Input;

use App\Http\Lib\SignLib;

use dekuan\delib\CLib;


/**
 *	Created by PhpStorm.
 *	User: liuqixing
 *	Date: July 3, 2017
 *	Time: 11:37 PM
 */
class ParameterLib
{
	//
	//	get safe page
	//
	static function getSafePage( $nPage, $nDefault = CConstantX::DEFAULT_PAGE )
	{
		return ( is_numeric( $nPage ) && $nPage > 0 ) ? intval( $nPage ) : $nDefault;
	}
	static function getSafePageSize( $nPageSize, $nDefault = CConstantX::DEFAULT_PAGE_SIZE )
	{
		return ( is_numeric( $nPageSize ) && $nPageSize > 0 ) ? intval( $nPageSize ) : $nDefault;
	}
	static function getSafePageStart( $nPage, $nPageSize )
	{
		return self::getSafePageSize( $nPageSize ) * ( self::getSafePage( $nPage ) - 1 );
	}
	
	//
	//	obtain parameter from URL/Route
	//
	static function obtainMIdParameter( $sName, $vDefault = null )
	{
		if ( ! is_string( $sName ) )
		{
			return $vDefault;
		}

		//	...
		$vRet	= Input::get( $sName, $vDefault );
		if ( ! CMIdLib::isValidMId( $vRet ) )
		{
			$vRet = request()->route( $sName );
		}

		return $vRet;
	}


	static function getCorrectedChinaRegionsValues( $arrAddress )
	{
		$arrRet	= null;
		
		if ( CLib::IsArrayWithKeys(
			$arrAddress,
			[ 'province_name', 'province_id', 'city_name', 'city_id', 'district_name', 'district_id', 'detail' ]
		) )
		{
			$arrRet	=
			[
				'province_name'	=> $arrAddress[ 'province_name' ],
				'province_id'	=> intval( $arrAddress[ 'province_id' ] ),
				'city_name'	=> $arrAddress[ 'city_name' ],
				'city_id'	=> intval( $arrAddress[ 'city_id' ] ),
				'district_name'	=> $arrAddress[ 'district_name' ],
				'district_id'	=> intval( $arrAddress[ 'district_id' ] ),
				'detail'	=> $arrAddress[ 'detail' ],
			];	
		}

		return $arrRet;
	}

	static function getImagesUrlNoPicture( $sFp = '' )
	{
		return sprintf( "%s?fp=%s", CommonLib::getDefaultPictureUrl(), strval( $sFp ) );		
	}

	static function decodeDbJsonArray( $objRecord, $sPropertyName )
	{
		$arrRet	= [];

		if ( CLib::IsObjectWithProperties( $objRecord, $sPropertyName ) &&
			CLib::IsExistingString( $objRecord->{ $sPropertyName } ) )
		{
			$arrRet = CLib::DecodeObject( $objRecord->{ $sPropertyName }, CLib::ENCODEOBJECT_TYPE_JSON );
		}
		
		return is_array( $arrRet ) ? $arrRet : [];
	}

	/**
	 *	@param	string	$sStatus
	 *	@return int
	 */
	static function getTaskOrderStatusByString( $sStatus )
	{
		if ( ! CLib::IsExistingString( $sStatus ) )
		{
			return CConstantX::TSKOD_ORDER_STATUS_UNKNOWN;
		}

		//	...
		$nRet		= CConstantX::TSKOD_ORDER_STATUS_UNKNOWN;
		$sStatus	= trim( $sStatus );
		
		if ( 0 == strcasecmp( 'todo', $sStatus ) )
		{
			$nRet = CConstantX::TSKOD_ORDER_STATUS_TODO;
		}
		else if ( 0 == strcasecmp( 'doing', $sStatus ) )
		{
			$nRet = CConstantX::TSKOD_ORDER_STATUS_DOING;
		}
		else if ( 0 == strcasecmp( 'done', $sStatus ) )
		{
			$nRet = CConstantX::TSKOD_ORDER_STATUS_DONE;
		}
		else if ( 0 == strcasecmp( 'rejected', $sStatus ) )
		{
			$nRet = CConstantX::TSKOD_ORDER_STATUS_REJECTED;
		}

		return $nRet;
	}


}