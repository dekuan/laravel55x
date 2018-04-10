<?php
namespace App\Http\Lib;

use App\Http\Middleware\Api\ApiCheckCSRF;
use dekuan\delib\CMIdLib;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

use dekuan\vdata\CResponse;
use dekuan\vdata\CRemote;
use dekuan\vdata\CConst;
use dekuan\delib\CLib;
use dekuan\delib\CMobileDetector;
use dekuan\delib\CEnv;


/**
 * Created by PhpStorm.
 * User: xing
 * Date: 23/04/2017
 * Time: 12:13 AM
 */
class NetworkLib
{
	static function isBrowserWeChat()
	{
		$cMd	= new CMobileDetector();
		$sVer	= $cMd->version( 'MicroMessenger' );

		//	...
		return CLib::IsExistingString( $sVer );
	}

	static function getSafeSource()
	{
		$nSource = CRemote::GetSource( Input::get() );
		return CConst::IsValidSource( $nSource ) ? intval( $nSource ) : CConst::SOURCE_UNKNOWN;
	}

	static function getSafeScheme()
	{
		return ( CEnv::IsSecureHttp() ? 'https' : 'http' );
	}
	static function getSafeHostName()
	{
		return CLib::GetValEx( $_SERVER, 'HTTP_HOST', CLib::VARTYPE_STRING, '' );
	}

	static function getAvatarUrl( $nUMId )
	{
		return sprintf
		(
			"%s://%s/%d",
			self::getSafeScheme(),
			ConfigLib::getHostAvatar(),
			( CMIdLib::isValidMId( $nUMId ) ? $nUMId : 0 )
		);
	}

	static function getClipUrl( $clip )
	{
		return sprintf
		(
			"%s://%s/%s",
			self::getSafeScheme(),
			ConfigLib::getHostClip(),
			$clip
		);
	}

	static function getSignInPageUrl()
	{
		return sprintf
		(
			"%s://%s/?ref=%s",
			self::getSafeScheme(),
			ConfigLib::getHostAccount(),
			urlencode( Request::fullUrl() )
		);
	}
	static function redirectToSignInPage()
	{
		return redirect( self::getSignInPageUrl() );
	}

	static function responseVData( $nErrorId, $sErrorDesc = '', $arrVData = [], $sVersion = '1.0', $bCached = null )
	{
		$cResponse	= CResponse::GetInstance();

		$cResponse->SetCorsDomains
		([
			'30mai.cn',
			'www.30mai.cn',
			'ui.call.30mai.cn',
		]);
		$arrExtra =
		[
			'token'	=>
			[
				'csrf'	=> ApiCheckCSRF::getInstance()->getToken()	
			]
		];

		return $cResponse->GetVDataResponse( $nErrorId, $sErrorDesc, $arrVData, $sVersion, $bCached, $arrExtra );
	}

	static function isValidVData( $arrJson )
	{
		return CResponse::GetInstance()->IsValidVData( $arrJson );
	}

	static function getSafeUrlRef( $arrParam, $sRootHost = CONFIG_XC_HOST_COOKIE )
	{
		$sRet = '';

		if ( CLib::IsArrayWithKeys( $arrParam, [ 'ref' ] ) &&
			CLib::IsExistingString(  $arrParam[ 'ref' ] ) &&
			6 < strlen( $arrParam['ref'] ) )
		{
			$sTmp	= trim( $arrParam['ref'] );
			$arrUrl	= parse_url( $sTmp );
			if ( CLib::IsArrayWithKeys( $arrUrl, [ 'scheme', 'host' ] ) )
			{
				if ( CLib::IsExistingString( $arrUrl['scheme'] ) &&
					in_array( $arrUrl['scheme'], [ 'http', 'https' ] ) )
				{
					if ( CLib::IsExistingString( $sRootHost ) )
					{
						$sRootHost	= trim( $sRootHost );
						$nHostLen	= strlen( $sRootHost );
						$sSubStr	= substr( $arrUrl[ 'host' ], -1 * $nHostLen );

						if ( 0 == strcasecmp( $sRootHost, $sSubStr ) )
						{
							$sRet = $arrParam['ref'];
						}
					}
					else
					{
						$sRet = $arrParam['ref'];
					}
				}
			}
		}

		return $sRet;
	}
}