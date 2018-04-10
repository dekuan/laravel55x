<?php
namespace App\Http\Lib;

use dekuan\vdata\CResponse;
use dekuan\vdata\CConst;
use dekuan\delib\CLib;


/**
 * Created by PhpStorm.
 * User: liuqixing
 * Date: 23/04/2017
 * Time: 11:21 AM
 */
class ConfigLib
{
	const DEFAULT_CORE_COOKIE_SEED 			= '580479c6eaa24-72937f31-a68e-488e-b0ad-623bd42ddd986ca';
	const DEFAULT_CORE_COOKIE_TIMEOUT_NORMAL	= 24 * 60 * 60;
	const DEFAULT_CORE_COOKIE_TIMEOUT_KEEP_ALIVE	= 365 * 24 * 60 * 60;



	////////////////////////////////////////////////////////////
	//	Host
	////////////////////////////////////////////////////////////

	static function getHostMain()
	{
		return config( 'xc.host.main', CONFIG_XC_HOST_MAIN );
	}
	static function getHostAccount()
	{
		return config( 'xc.host.account', CONFIG_XC_HOST_ACCOUNT );
	}
	static function getHostAvatar()
	{
		return config( 'xc.host.avatar', CONFIG_XC_HOST_AVATAR );
	}
	static function getHostCookie()
	{
		return config( 'xc.host.cookie', CONFIG_XC_HOST_COOKIE );
	}
	static function getHostClip()
	{
		return config( 'xc.host.clip', CONFIG_XC_HOST_CLIP );
	}
	static function getHostVideo()
	{
		return config( 'xc.host.video', CONFIG_XC_HOST_VIDEO );
	}




	////////////////////////////////////////////////////////////
	//	Url
	////////////////////////////////////////////////////////////

	static function getUrlByHost( $sHost )
	{
		return sprintf( "%s://%s/", NetworkLib::getSafeScheme(), ( CLib::IsExistingString( $sHost ) ? $sHost : '' ) );
	}

	static function getUrlAccessAvatar()
	{
		return self::getUrlByHost( config( 'xc.host.avatar' ) );
	}
	static function getUrlAccessAvatarWithUMId( $nUMId )
	{
		return sprintf( "%s%d.jpg?x-oss-process=style/m", self::getUrlAccessAvatar(), intval( $nUMId ) );
	}

	static function getUrlAccessObject()
	{
		return self::getUrlByHost( config( 'xc.host.object' ) );
	}
	static function getUrlAccessMain()
	{
		return self::getUrlByHost( self::getHostMain() );
	}
	static function getUrlAccessCallUI()
	{
		return self::getUrlByHost( config( 'xc.host.call_ui' ) );
	}
	static function getUrlAccessClip()
	{
		return self::getUrlByHost( self::getHostClip() );
	}
	static function getUrlAccessVideo()
	{
		return self::getUrlByHost( self::getHostVideo() );
	}

	
	

	////////////////////////////////////////////////////////////
	//	Cookies
	////////////////////////////////////////////////////////////

	static function getCoreCookieSeed()
	{
		return config( 'xc.core.cookie_seed', self::DEFAULT_CORE_COOKIE_SEED );
	}
	static function getCoreCookieTimeoutNormal()
	{
		return config( 'xc.core.cookie_timeout_normal', self::DEFAULT_CORE_COOKIE_TIMEOUT_NORMAL );
	}
	static function getCoreCookieTimeoutKeepAlive()
	{
		return config( 'xc.core.cookie_timeout_keep_alive', self::DEFAULT_CORE_COOKIE_TIMEOUT_KEEP_ALIVE );
	}



	////////////////////////////////////////////////////////////
	//	Group Level
	////////////////////////////////////////////////////////////

	static function getGroupLevel( $nLevel )
	{
		$arrRet	= null;
		$sKey	= '';

		if ( is_numeric( $nLevel ) )
		{
			$sKey	= sprintf( "xglevel.%d", $nLevel );
			$arrRet	= config( $sKey, null );
			if ( ! is_array( $arrRet ) )
			{
				$sKey	= sprintf( "xglevel.%d", 0 );
				$arrRet	= config( $sKey, null );
			}
		}
		else
		{
			$sKey	= "xglevel";
			$arrRet	= config( $sKey, null );
		}

		return $arrRet;
	}
	static function getGroupLevelList()
	{
		return config( "xglevel", null );
	}
	static function getGroupLevelCount()
	{
		$arrList = self::getGroupLevelList();
		return ( is_array( $arrList ) ? count( $arrList ) : 0 );
	}



}