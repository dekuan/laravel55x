<?php

namespace App\Http\Middleware\Api;

use App\Http\Lib\ConfigLib;
use Closure;
use Illuminate\Session\TokenMismatchException;

use App\Http\Constants\CErrCodeMiddleware;

use App\Http\Lib\NetworkLib;
use App\Http\Lib\LangLib;


use dekuan\delib\CLib;


/***
 *	Class ApiCheckCSRF
 *	@package App\Http\Middleware\Api
 */
class ApiCheckCSRF
{
	protected static $g_cStaticInstance;

	/**
	 *	The URIs that should be excluded from CSRF verification.
	 *
	 *	@var array
	 */
	protected $m_arrExcept =
	[
		'api/*',
	];


	static function getInstance()
	{
		if ( is_null( self::$g_cStaticInstance ) || ! isset( self::$g_cStaticInstance ) )
		{
			self::$g_cStaticInstance = new self();
		}
		return self::$g_cStaticInstance;
	}


	public function handle( $request, Closure $next )
	{
		if ( $this->isReading( $request ) ||
			$this->shouldPassThrough( $request ) ||
			$this->tokensMatch( $request ) )
		{
			return $this->nextResponse( $request, $next( $request ) );
		}

		//	...
		$nErrorId	= CErrCodeMiddleware::APIANTICSRF_INVALID_TOKEN;
		$sErrorDesc	= LangLib::getErrorDesc( 'api', '_middleware', $nErrorId );

		return NetworkLib::responseVData( $nErrorId, $sErrorDesc );
	}
	
	
	public function getToken()
	{
		$nIpValue	= ip2long( CLib::GetClientIP( false, true ) );
		$nExpire	= time() + 60 * 60;
		$sRandom	= sprintf( "%s%s", strval( sha1( microtime() ) ), strval( md5( microtime() ) ) );
		$sSignature	= $this->getSignature( $nIpValue, $nExpire, $sRandom );

		return sprintf( "%d.%d.%s.%s", $nIpValue, $nExpire, $sRandom, $sSignature );
	}
	public function getSignature( $nIpValue, $nExpire, $sRandom )
	{
		$sSrc		= sprintf( "ipv=%d,expire=%d,random=%s", $nIpValue, $nExpire, $sRandom );
		$sSha256	= hash( 'sha256', $sSrc );
		return sprintf( "%s%s", $sSha256, md5( $sSha256 ) );
	}
	public function isValidToken( $sToken )
	{
		if ( ! is_string( $sToken ) )
		{
			return false;
		}

		$bRet = false;
		$arrItem = explode( '.', $sToken );
		if ( is_array( $arrItem ) && 4 == count( $arrItem ) )
		{
			$nIpValue	= $arrItem[ 0 ];
			$nExpire	= $arrItem[ 1 ];
			$sRandom	= $arrItem[ 2 ];
			$sSignature	= $arrItem[ 3 ];

			if ( is_numeric( $nIpValue ) &&
				is_numeric( $nExpire ) && $nExpire > time() &&
				is_string( $sRandom ) && ! empty( $sRandom ) &&
				is_string( $sSignature ) && ! empty( $sSignature ) )
			{
				$sSignatureT	= $this->getSignature( $nIpValue, $nExpire, $sRandom );
				if ( 0 == strcasecmp( $sSignature, $sSignatureT ) )
				{
					$bRet = true;
				}
			}
		}

		return $bRet;
	}




	/**
	 * Determine if the request has a URI that should pass through CSRF verification.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return bool
	 */
	protected function shouldPassThrough( $request )
	{
		foreach ( $this->m_arrExcept as $sExcept )
		{
			if ( '/' !== $sExcept )
			{
				$sExcept = trim( $sExcept, '/' );
			}
			if ( $request->is( $sExcept ) )
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Determine if the session and input CSRF tokens match.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return bool
	 */
	protected function tokensMatch($request)
	{
		$sToken = $request->input( 'csrf_token' ) ?: $request->header( 'X-CSRF-TOKEN' );
		return $this->isValidToken( $sToken );
	}

	/**
	 * Add the CSRF token to the response cookies.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Illuminate\Http\Response  $response
	 * @return \Illuminate\Http\Response
	 */
	protected function nextResponse( $request, $response )
	{
		return $response;
	}

	/**
	 * Determine if the HTTP request uses a ‘read’ verb.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return bool
	 */
	protected function isReading($request)
	{
		return in_array( $request->method(), [ 'HEAD', 'GET', 'OPTIONS' ] );
	}
}