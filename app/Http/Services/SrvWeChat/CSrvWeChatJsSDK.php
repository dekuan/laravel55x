<?php

namespace App\Http\Services\SrvWeChat;

use dekuan\vdata\CConst;
use dekuan\delib\CLib;
use dekuan\vdata\CRequest;


/**
 *	class of CSrvWeChatJsSDK
 */
class CSrvWeChatJsSDK
{
	private $m_sAppId;
	private $m_sAppSecret;

	public function __construct( $sAppId, $sAppSecret )
	{
		$this->m_sAppId		= $sAppId;
		$this->m_sAppSecret	= $sAppSecret;
	}

	public function GetSignData( callable $pfnPersistentTicket, callable $pfnPersistentToken )
	{
		//
		//	pfnPersistentTicket	- [in] array	callable function addresses to get/save persistent ticket value
		//				[
		//					'get'	=> callable function address to get ...
		//					'save'	=> callable function address to save ...
		//				]
		//	pfnPersistentToken	- [in] array	callable function addresses to get/save persistent token value
		//				[
		//					'get'	=> callable function address to get ...
		//					'save'	=> callable function address to save ...
		//				]
		//	RETURN			- ticket
		//
		$sJsApiTicket	= $this->_GetJsApiTicket( $pfnPersistentTicket, $pfnPersistentToken );
		$sUrl		= $this->_GetSelfUrl();
		$nTimestamp	= time();
		$sNonceStr	= $this->_GetNonceStr();
		$sSignature	= $this->_GetSign( $sJsApiTicket, $sNonceStr, $nTimestamp, $sUrl );

		return
		[
			'appid'		=> $this->m_sAppId,
			'noncestr'	=> $sNonceStr,
			'timestamp'	=> $nTimestamp,
			'url'		=> $sUrl,
			'signature'	=> $sSignature,
		];
	}

	////////////////////////////////////////////////////////////////////////////////
	//	private
	//

	private function _GetSign( $sJsApiTicket, $sNonceStr, $nTimestamp, $sUrl )
	{
		if ( ! is_string( $sJsApiTicket ) && ! is_numeric( $sJsApiTicket ) )
		{
			return '';
		}
		if ( ! is_string( $sNonceStr ) && ! is_numeric( $sNonceStr ) )
		{
			return '';
		}
		if ( ! is_string( $nTimestamp ) && ! is_numeric( $nTimestamp ) )
		{
			return '';
		}
		if ( ! is_string( $sUrl ) && ! is_numeric( $sUrl ) )
		{
			return '';
		}

		//	这里参数的顺序要按照 key 值 ASCII 码升序排序
		$sString = sprintf
		(
			"jsapi_ticket=%s&noncestr=%s&=%s&url=%s",
			$sJsApiTicket, $sNonceStr, $nTimestamp, $sUrl
		);

		return sha1( $sString );
	}
	private function _GetSelfUrl()
	{
		$sHost	= '';
		$sUri	= '';

		if ( is_array( $_SERVER ) && array_key_exists( 'HTTP_HOST', $_SERVER ) )
		{
			$sHost	= $_SERVER[ 'HTTP_HOST' ];
		}
		if ( is_array( $_SERVER ) && array_key_exists( 'REQUEST_URI', $_SERVER ) )
		{
			$sUri	= $_SERVER[ 'REQUEST_URI' ];
		}

		return sprintf( "http://%s%s", $sHost, $sUri );
	}

	private function _GetNonceStr( $nLength = 16 )
	{
		$sRet	= '';
		$sChars	= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

		for ( $i = 0; $i < $nLength; $i ++ )
		{
			$sRet .= substr( $sChars, mt_rand( 0, strlen( $sChars ) - 1 ), 1 );
		}

		return $sRet;
	}

	private function _GetJsApiTicket( callable $pfnPersistentTicket, callable $pfnPersistentToken )
	{
		//
		//	pfnPersistentTicket	- [in] array	callable function addresses to get/save persistent ticket value
		//				[
		//					'get'	=> callable function address to get ...
		//					'save'	=> callable function address to save ...
		//				]
		//	pfnPersistentToken	- [in] array	callable function addresses to get/save persistent token value
		//				[
		//					'get'	=> callable function address to get ...
		//					'save'	=> callable function address to save ...
		//				]
		//	RETURN			- ticket
		//
		//	NOTE
		//	jsapi_ticket 的有效期为 7200 秒
		//
		$sRet		= '';
		$sPValue	= '';

		if ( ! CLib::IsArrayWithKeys( $pfnPersistentTicket, [ 'get', 'save' ] ) )
		{
			return '';
		}
		if ( ! CLib::IsArrayWithKeys( $pfnPersistentToken, [ 'get', 'save' ] ) )
		{
			return '';
		}

		//
		//	try to get ticket from persistent storage
		//
		if ( is_callable( $pfnPersistentTicket['get'] ) )
		{
			$sPValue = $pfnPersistentTicket['get']();
			if ( ! is_string( $sPValue ) && ! is_numeric( $sPValue ) )
			{
				$sPValue = '';
			}
		}

		if ( strlen( $sPValue ) > 0 )
		{
			//
			//	pick up the value from persistent storage
			//
			$sRet = $sPValue;
		}
		else
		{
			//
			//	obtain a fresh ticket via a RPC call
			//
			$sTicket = $this->_GetJsApiTicketFromServer( $pfnPersistentToken );
			if ( is_string( $sTicket ) || is_numeric( $sTicket ) )
			{
				if ( strlen( $sTicket ) > 0 )
				{
					$sRet = $sTicket;

					//
					//	save the ticket to persistent storage
					//
					if ( is_callable( $pfnPersistentTicket['save'] ) )
					{
						$pfnPersistentTicket['save']( $sTicket );
					}
				}
			}
		}

		return $sRet;
	}
	private function _GetJsApiTicketFromServer( callable $pfnPersistentToken )
	{
		//
		//	pfnPersistentToken	- [in] array	callable function addresses to get/save persistent token value
		//				[
		//					'get'	=> callable function address to get ...
		//					'save'	=> callable function address to save ...
		//				]
		//	RETURN		- ticket
		//
		//	NOTE
		//	jsapi_ticket 的有效期为 7200 秒
		//
		$sRet		= '';

		if ( ! CLib::IsArrayWithKeys( $pfnPersistentToken, [ 'get', 'save' ] ) )
		{
			return '';
		}

		//
		//	obtain a fresh ticket via a RPC call
		//
		$sAccessToken	= $this->_GetAccessToken( $pfnPersistentToken );
		if ( is_string( $sAccessToken ) || is_numeric( $sAccessToken ) )
		{
			$sUrl = sprintf
			(
				"https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=%s&access_token=%s",
				"jsapi",
				$sAccessToken
			);
			$arrRes = @ json_decode( $this->_HttpGet( $sUrl ), true );
			if ( is_array( $arrRes ) && array_key_exists( 'ticket', $arrRes ) )
			{
				$sTicket = $arrRes[ 'ticket' ];
				if ( is_string( $sTicket ) || is_numeric( $sTicket ) )
				{
					if ( strlen( $sTicket ) > 0 )
					{
						$sRet = $sTicket;
					}
				}
			}
		}

		return $sRet;
	}


	private function _GetAccessToken( callable $pfnPersistentToken )
	{
		//
		//	pfnPersistentSave	- [in] callable function address to save persistent value
		//	RETURN			- ticket
		//
		//	NOTE
		//	jsapi_ticket 的有效期为 7200 秒
		//
		if ( ! CLib::IsArrayWithKeys( $pfnPersistentToken, [ 'get', 'save' ] ) )
		{
			return '';
		}

		//	...
		$sRet		= '';
		$sPValue	= '';

		//
		//	try to get ticket from persistent storage
		//
		if ( is_callable( $pfnPersistentToken['get'] ) )
		{
			$sPValue = $pfnPersistentToken['get']();
			if ( ! is_string( $sPValue ) && ! is_numeric( $sPValue ) )
			{
				$sPValue = '';
			}
		}

		if ( strlen( $sPValue ) > 0 )
		{
			//
			//	pick up the value from persistent storage
			//
			$sRet = $sPValue;
		}
		else
		{
			//
			//	obtain a fresh ticket via a RPC call
			//
			$sAccessToken = $this->_GetAccessTokenFromServer();
			if ( is_string( $sAccessToken ) || is_numeric( $sAccessToken ) )
			{
				if ( strlen( $sAccessToken ) > 0 )
				{
					$sRet = $sAccessToken;

					//
					//	save the ticket to persistent storage
					//
					if ( is_callable( $pfnPersistentToken['save'] ) )
					{
						$pfnPersistentToken['save']( $sAccessToken );
					}
				}
			}
		}

		return $sRet;
	}
	private function _GetAccessTokenFromServer()
	{
		//	...
		$sRet	= '';

		//	...
		$sUrl = sprintf
		(
			"https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s",
			$this->m_sAppId,
			$this->m_sAppSecret
		);
		$arrRes = @ json_decode( $this->_HttpGet( $sUrl ), true );
		if ( is_array( $arrRes ) && array_key_exists( 'access_token', $arrRes ) )
		{
			$sAccessToken	= $arrRes[ 'access_token' ];
			if ( is_string( $sAccessToken ) || is_numeric( $sAccessToken ) )
			{
				if ( strlen( $sAccessToken ) > 0 )
				{
					//	successfully
					$sRet = $sAccessToken;
				}
			}
		}

		return $sRet;
	}

	private function _HttpGet( $sUrl, $nTimeout = 5 )
	{
		if ( ! is_string( $sUrl ) || 0 == strlen( $sUrl ) )
		{
			return '';
		}
		if ( ! is_numeric( $nTimeout ) )
		{
			return '';
		}

		$sRet		= '';
		$cRequest	= CRequest::GetInstance();
		$arrRaw		= [];
		$nCall		= $cRequest->HttpRaw
		(
			[
				'method'	=> 'GET',
				'url'		=> $sUrl,
				'data'		=> [],
				'version'	=> '1.0',				//  required version of service
				'timeout'	=> $nTimeout,	//  timeout in seconds
				'cookie'	=> [],				//  array or string are both okay.
			],
			$arrRaw
		);
		if ( CConst::ERROR_SUCCESS == $nCall &&
			$cRequest->IsValidRawResponse( $arrRaw ) )
		{
			//	$arrRaw
			//		'data'		: HTTP body/contents
			//		'status'	: HTTP status code
			//		'headers'	: responded headers
			$sRet	= $arrRaw['data'];
		}

		return $sRet;
	}

}