<?php

namespace App\Http\Lib;

use dekuan\delib\CMIdLib;
use Lang;

use App\Http\Constants\CErrCodeLib;

use dekuan\vdata\CConst;
use dekuan\delib\CLib;
use dekuan\deuclientpro\UCProConst;
use dekuan\deuclientpro\UClientPro;


/***
 *	Class UserLib
 *	@package App\Http\Lib
 */
class UserLib
{
	static function getUCInfo()
	{
		return Array
		(
			'u_mid'		=> UserLib::getLoggedInUMId(),
			'u_nickname'	=> UserLib::getLoggedInUNickname(),
			'avatar_url'	=> NetworkLib::getAvatarUrl( UserLib::getLoggedInUMId() ),
		);
	}
	
	static function getConfiguredUCInstance()
	{
		$cUClient	= UClientPro::getInstance();

		//	...
		$cUClient->SetConfig( UCProConst::CONFIG_COOKIE_DOMAIN, ConfigLib::getHostCookie() );
		$cUClient->SetConfig( UCProConst::CONFIG_COOKIE_SEED, ConfigLib::getCoreCookieSeed() );

		return $cUClient;
	}

	//
	//	send cookie to client
	//
	static function makeLogin( $arrUInfo, $bKeepAlive, & $sCkString = '', & $nSrvErrCode = CErrCodeLib::USERLIB_ERROR_MAKE_LOGIN_SRV_FAILED )
	{
		//
		//	arrUInfo	- [in] array	user info
		//	bKeepAlive	- [in] bool	is keep alive for cookie ?
		//	sCkString	- [out] string	a string contains the full XT cookie
		//	RETURN		- error id
		//
		if ( ! CLib::IsArrayWithKeys( $arrUInfo, [ 'u_mid', 'u_nickname', 'u_type', 'u_status', 'u_action', 'u_source' ] ) )
		{
			return CErrCodeLib::USERLIB_ERROR_INVALID_UINFO;
		}
		if ( ! CLib::IsExistingString( $arrUInfo[ 'u_mid' ] ) )
		{
			return CErrCodeLib::USERLIB_ERROR_INVALID_UINFO_U_MID;
		}

		//	...
		$nRet = CErrCodeLib::USERLIB_ERROR_MAKE_LOGIN_FAILED;

		//	...
		$cUClientPro	= self::getConfiguredUCInstance();

		//	..
		$nTimeoutKeepAlive	= ConfigLib::getCoreCookieTimeoutKeepAlive();
		$nTimeoutNormal		= ConfigLib::getCoreCookieTimeoutNormal();

		//	...
		$nLoginTime	= time();
		$nRefreshTime	= $nLoginTime + ( $bKeepAlive ? $nTimeoutKeepAlive : $nTimeoutNormal );
		$nUpdateTime	= $nRefreshTime;
		$arrData	= Array
		(
			UCProConst::CKX => Array
			(
				UCProConst::CKX_MID		=> $arrUInfo[ 'u_mid' ],
				UCProConst::CKX_NICKNAME	=> $arrUInfo[ 'u_nickname' ],
				UCProConst::CKX_TYPE		=> $arrUInfo[ 'u_type' ],
				UCProConst::CKX_AVATAR		=> $arrUInfo[ 'u_mid' ],
				UCProConst::CKX_STATUS		=> $arrUInfo[ 'u_status' ],
				UCProConst::CKX_ACTION		=> $arrUInfo[ 'u_action' ],
				UCProConst::CKX_SRC		=> $arrUInfo[ 'u_source' ],
				UCProConst::CKX_DIGEST		=> 'digest',
			),
			UCProConst::CKT => Array
			(
				UCProConst::CKT_LOGIN_TM	=> $nLoginTime,
				UCProConst::CKT_REFRESH_TM	=> $nRefreshTime,
				UCProConst::CKT_UPDATE_TM	=> $nUpdateTime,
				UCProConst::CKT_KP_ALIVE	=> ( $bKeepAlive ? 1 : 0 ),
				UCProConst::CKT_SS_ID		=> 0,
			),
		);

		$sCkString	= '';
		$nSrvErrCode	= $cUClientPro->MakeLogin( $arrData, $bKeepAlive, $sCkString );
		if ( CConst::ERROR_SUCCESS == $nSrvErrCode )
		{
			//
			//	make login successfully
			//
			$nRet = CConst::ERROR_SUCCESS;
		}
		else
		{
			$nRet = CErrCodeLib::USERLIB_ERROR_MAKE_LOGIN_FAILED;
		}

		return $nRet;
	}

	//
	//	is logged in
	//
	static function isLoggedIn()
	{
		return ( CConst::ERROR_SUCCESS == UserLib::getConfiguredUCInstance()->CheckLogin() );
	}

	//
	//	get u_mid of user who already logged in
	//
	static function getLoggedInUMId()
	{
		if ( ! self::isLoggedIn() )
		{
			return 0;
		}

		//	...
		$nUMId = self::getUCCookieXValue( UCProConst::CKX_MID );
		return ( CMIdLib::isValidMId( $nUMId ) ? intval( $nUMId ) : 0 );
	}

	static function getLoggedInUNickname()
	{
		return self::getUCCookieXValue( UCProConst::CKX_NICKNAME );
	}

	static function getUCCookieXValue( $sKey )
	{
		if ( ! CLib::IsExistingString( $sKey, true ) )
		{
			return '';
		}

		$sRet		= '';
		$cUClientPro	= UserLib::getConfiguredUCInstance();
		$nErrorId	= $cUClientPro->CheckLogin();

		if ( CConst::ERROR_SUCCESS == $nErrorId )
		{
			$sKey	= trim( $sKey );
			$sRet	= $cUClientPro->getXValue( $sKey );
		}

		return $sRet;
	}
	static function getUCCookieTValue( $sKey )
	{
		if ( ! CLib::IsExistingString( $sKey, true ) )
		{
			return '';
		}

		$sRet		= '';
		$cUClientPro	= UserLib::getConfiguredUCInstance();
		$nErrorId	= $cUClientPro->CheckLogin();
		if ( CConst::ERROR_SUCCESS == $nErrorId )
		{
			$sKey	= trim( $sKey );
			$sRet	= $cUClientPro->getTValue( $sKey );
		}

		return $sRet;
	}

	static function isValidOAuthUsername( $sStr )
	{
		return self::isValidGivenString( $sStr, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_" );
	}
	static function isValidGivenString( $sCheckStr, $sStdChars )
	{
		//
		//	sStr	- [in] string
		//	RETURN	- true / false
		//
		$bRet = false;

		if ( CLib::IsExistingString( $sCheckStr ) &&
			CLib::IsExistingString( $sStdChars, true ) )
		{
			$nStrLength	= strlen( $sCheckStr );
			$nErrorCount	= 0;
			for ( $i = 0; $i < $nStrLength; $i ++ )
			{
				$cChr = substr( $sCheckStr, $i, 1 );
				if ( ! strstr( $sStdChars, $cChr ) )
				{
					$nErrorCount ++;
					break;
				}
			}

			//	...
			$bRet = ( 0 == $nErrorCount ? true : false );
		}

		return $bRet;
	}
//
	//	get u_mid of user who already logged in
	//
	static function getUCMId()
	{
		if ( ! self::isLoggedIn() )
		{
			return 0;
		}

		//	...
		$nUMId = self::getUCCookieXValue( UCProConst::CKX_MID );
		return ( CMIdLib::isValidMId( $nUMId ) ? intval( $nUMId ) : 0 );
	}

	//
	//	check if it's a valid verify code
	//
	static function isValidVerifyCode( $vCode )
	{
		return ( ( is_numeric( $vCode ) && $vCode > 0 ) ||
			CLib::IsExistingString( $vCode ) );
	}

	static function getNumericRandomCode( $nLength = 6 )
	{
		if ( ! is_numeric( $nLength ) || $nLength <= 0 )
		{
			return 0;
		}

		$nLength = min( $nLength, 18 );
		return rand( pow( 10, ( $nLength - 1 ) ), pow( 10, $nLength ) - 1 );
	}
}
