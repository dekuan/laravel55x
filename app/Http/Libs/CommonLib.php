<?php

namespace App\Http\Lib;

use dekuan\delib\CLib;


/***
 *	Class CommonLib
 *	@package App\Http\Lib
 */
class CommonLib
{
	static function getJoinPassword()
	{
		return substr( strval( abs( crc32( date( "Y-m-d H" ) ) ) ), 0, 4 );
	}

	static function isValidChinaPhoneNumber( $sStr )
	{
		return ( CLib::IsExistingString( $sStr ) && 1 == preg_match( "/^1[34578]\d{9}$/", $sStr ) );
	}
	
	static function isValidFilename( $sStr )
	{
		//
		//	sStr	- [in] string
		//	RETURN	- true / false
		//
		$bRet = false;
		$sStdChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_";

		if ( CLib::IsExistingString( $sStr, true ) )
		{
			$sStr		= trim( $sStr );
			$nStrLength	= strlen( $sStr );
			$nErrorCount	= 0;
			for ( $i = 0; $i < $nStrLength; $i ++ )
			{
				$cChr = substr( $sStr, $i, 1 );
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

	
	static function isValidDbOrderByDirection( $sDirection )
	{
		return ( CLib::IsExistingString( $sDirection ) &&
			( 0 == strcasecmp( 'DESC', $sDirection ) || 0 == strcasecmp( 'ASC', $sDirection ) ) );
	}

	
	static function getJsonItemValue( $vJson, $vKey )
	{
		$sRet = '';

		if ( is_string( $vKey ) || is_numeric( $vKey ) )
		{
			if ( is_string( $vJson ) )
			{
				$vJson = @ json_decode( $vJson, true );
			}
			if ( CLib::IsArrayWithKeys( $vJson, $vKey ) )
			{
				$sRet = $vJson[ $vKey ];
			}
		}

		return $sRet;
	}

	static function getDefaultPictureUrl( $sFp = '' )
	{
		return sprintf( "%s/images/no-picture.png", rtrim( ConfigLib::getUrlAccessMain(), "\r\n\t /" ) );
	}

        static function getEditorExpireConfirm()
	{
		return date('Y-m-d H:i:s',strtotime('+12 hours'));
	}
	static function getExpireFinish()
	{
		return date('Y-m-d H:i:s',strtotime( "+2 days"));
	}
	static function getExpirePayBill()
	{
		return date('Y-m-d H:i:s',strtotime( "+7 days"));
	}

	static function getClearCellPhoneNumber( $sMobile )
	{
		if ( ! is_string( $sMobile ) || 0 == strlen( $sMobile ) )
		{
			return '';
		}

		//	...
		$sRet		= '';
		$nStrLength	= strlen( $sMobile );
		for ( $i = 0; $i < $nStrLength; $i ++ )
		{
			$cChr = substr( $sMobile, $i, 1 );
			if ( is_numeric( $cChr ) )
			{
				$sRet .= $cChr;
			}
		}

		return substr( $sRet, -11 );
	}
	
	
	
}