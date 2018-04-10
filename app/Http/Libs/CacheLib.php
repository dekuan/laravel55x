<?php

namespace App\Http\Lib;

use Illuminate\Support\Facades\Cache;
use dekuan\delib\CLib;



/**
 *	Class CacheLib
 *	@package App\Http\Lib
 */
class CacheLib
{
	/***
	 *	@param	string	$sSubKey
	 *	@param	bool	$bUseAuthenticationSystem
	 *	@return	string
	 */
	static function getCacheKey( $sSubKey, $bUseAuthenticationSystem = true )
	{
		if ( ! CLib::IsExistingString( $sSubKey ) )
		{
			return '';
		}

		//	...
		$sRet		= '';
		$sNamespace	= CONFIG_XC_HOST_CALL_API;

		if ( $bUseAuthenticationSystem )
		{
			//
			//	force to use user identity authentication system
			//
			$nUMId	= UserLib::getLoggedInUMId();
			$sRet	= sprintf( "%s-%d-%s", $sNamespace, $nUMId, $sSubKey );
		}
		else
		{
			$sRet	= sprintf( "%s--%s", $sNamespace, $sSubKey );
		}

		//	...
		return $sRet;
	}
	
	/**
	 *	get cache value
	 *
	 *	@param	string		$sKey
	 *	@return null|array
	 * 			[
	 * 				'd'	=> cached value
	 * 			]
	 *
	 * 	Example:
	 *
	 * 	$sCacheKey	= CacheLib::getCacheKey( sprintf("user_table-u_mid__mob-%s", $sULoginMobile ) );
	 *	$nCacheMinutes	= 60 * 24 * 365;	//	in minutes
	 *	$arrCacheValue	= CacheLib::getCacheValue( $sCacheKey );
	 *	if ( CLib::IsArrayWithKeys( $arrCacheValue, 'd' ) )
	 *	{
	 *		$nRet	= $arrCacheValue[ 'd' ];
	 *	}
	 *	else
	 *	{
	 *		$nRet	= $this->coreGetUMIdByULoginMobile( $sULoginMobile );
	 *		CacheLib::setCacheValue( $sCacheKey, $nRet, 60 );
	 *	}
	 */
	static function getCacheValue( $sKey )
	{
		$vRet = null;

		if ( CLib::IsExistingString( $sKey ) )
		{
			$vRet = Cache::store('redis')->get( $sKey );
		}

		return $vRet;
	}
	
	/**
	 *	set cache value
	 *
	 *	@param	string		$sKey
	 *	@param	mixed		$vValue
	 *	@param	int		$vExpires	in minutes
	 * 		DateTime	$expiresAt = now()->addMinutes(10);
	 *	@return null
	 */
	static function setCacheValue( $sKey, $vValue, $vExpires = 0 )
	{
		$vRet = null;
		
		if ( CLib::IsExistingString( $sKey ) )
		{
			$vRet = Cache::store('redis')->put( $sKey, [ 'd' => $vValue ], $vExpires );
		}

		return $vRet;
	}
	
	/**
	 * 	clear cache value
	 *
	 *	@param	string	$sKey
	 *	@return	mixed
	 */
	static function clearCacheValue( $sKey )
	{
		$vRet = null;

		if ( CLib::IsExistingString( $sKey ) )
		{
			$vRet = Cache::store('redis')->forget( $sKey );
		}

		return $vRet;
	}

}