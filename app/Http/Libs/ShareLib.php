<?php
namespace App\Http\Lib;

use Illuminate\Support\Facades\Cache;

use dekuan\vdata\CConst;
use dekuan\delib\CLib;
use dekuan\xssharesdk\CWXJSSDK;



/**
 *	Created by PhpStorm.
 *	User: xing
 *	Date: 12:13 AM December 3, 2017
 */
class ShareLib
{
	const WX_APP_ID		= 's';
	const WX_APP_SECRET	= 's';
	const WX_CACHE_MINUTES	= 100;	//	100 minutes


	static function getWeChatShareData( & $arrDataReturn = null )
	{
		$arrRet		= null;
		$nErrorId	= CWXJSSDK::GetInstance()->SetConfig( self::WX_APP_ID, self::WX_APP_SECRET );
		$arrDataReturn	= null;

		if ( CConst::ERROR_SUCCESS == $nErrorId )
		{
			$nErrorId = CWXJSSDK::GetInstance()->GetSignData
			(
				//	ticket
				[
					'get'	=> function()
					{
						//
						//	get access token from persistent storage
						//
						$sTocket	= '';
						$arrCacheData	= Cache::store( 'redis' )->get( 'cache-ticket' );
						if ( is_array( $arrCacheData ) && array_key_exists( 'd', $arrCacheData ) )
						{
							$arrRtn	= is_array( $arrCacheData[ 'd' ] ) ? $arrCacheData[ 'd' ] : [];
							if ( CLib::IsArrayWithKeys( $arrRtn, 'ticket' ) )
							{
								$sTocket = $arrRtn[ 'ticket' ];
							}
						}
						
						return $sTocket;
					},
					'save'	=> function( $sValue )
					{
						//	save access token to persistent storage, if necessarily
						Cache::store( 'redis' )->put( 'cache-ticket', [ 'd' => ['ticket' => $sValue ] ], self::WX_CACHE_MINUTES );
						return true;
					},
				],

				//	Token
				[
					'get'	=> function()
					{
						//	get ticket from persistent storage
						$sToken		= '';
						$arrCacheData	= Cache::store( 'redis' )->get( 'cache-token' );
						if ( CLib::IsArrayWithKeys( $arrCacheData, 'd' ) )
						{
							$arrRtn = is_array( $arrCacheData[ 'd' ] ) ? $arrCacheData[ 'd' ] : [];
							if( CLib::IsArrayWithKeys( $arrRtn, 'token' ) )
							{
								$sToken = $arrRtn[ 'token' ];
							}
						}
						
						return $sToken;
					},
					'save'	=> function( $sValue )
					{
						//	save ticket to persistent storage, if necessarily
						Cache::store( 'redis' )->put( 'cache-token', [ 'd' => ['token' => $sValue] ], self::WX_CACHE_MINUTES );
						return true;
					},
				],
				
				//	write back value
				$arrDataReturn
			);
		}
		
		return $nErrorId;
	}



}