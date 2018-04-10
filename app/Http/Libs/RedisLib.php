<?php

namespace App\Http\Lib;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

use App\Http\Constants\CErrCodeLib;
use App\Http\Constants\CConstantX;
use App\Http\Constants\CErrCodeDispatcher;

use dekuan\vdata\CConst;
use dekuan\delib\CLib;
use dekuan\delib\CMIdLib;


/**
 *	Class RedisLib
 *	@package App\Http\Lib
 */
class RedisLib
{
	//  redis set k-v
	//
	public  static function _setVTopSetContent( $nVMid, $nVCountType )
	{

		if ( ! CMIdLib::isValidMId( $nVMid ) )
		{
			return CErrCodeLib::REDISLIB_ERROR_SETVTOPSETCONTENT_PARAM_CONTENT_NVMID;
		}
		if ( ! CConstantX::isValidCountType( $nVCountType ) )
		{
			return CErrCodeLib::REDISLIB_ERROR_SETVTOPSETCONTENT_PARAM_CONTENT_TYPE;
		}

		//	...
		$sKey	= sprintf( "v-top-mid-%d", $nVMid );
		$sValue		= Redis::hkeys( $sKey );
		// searching
		// add item
		if( $sValue )
		{
			if ( $nVCountType == CConstantX::V_COUNT_TYPE_PLAY)
			{
				$nHSet		= Redis::hincrbyfloat( $sKey, 'vt_count_play', 1 );
			}
			if ( $nVCountType == CConstantX::V_COUNT_TYPE_SET)
			{
				$nHSet		= Redis::hincrbyfloat( $sKey, 'vt_count_set', 1 );
			}
			if ( $nVCountType == CConstantX::V_COUNT_TYPE_DOWNLOAD)
			{
				$nHSet		= Redis::hincrbyfloat( $sKey, 'vt_count_download', 1 );
			}
		}
		else
		{
			$nPlay		= CConstantX::V_COUNT_TYPE_PLAY == $nVCountType ? 1 : 0;
			$nSet		= CConstantX::V_COUNT_TYPE_SET == $nVCountType ? 1 : 0;
			$nDownload	= CConstantX::V_COUNT_TYPE_DOWNLOAD == $nVCountType ? 1 : 0;
			$video		= ConfigLib::getUrlAccessObject();

			$arrQ	=DB::select( "SELECT vt_song_name, vt_song_artist, vt_song_album, CONCAT('".$video."',
			                    vt_oss_key_video) AS vt_video_url, CONCAT('".$video."',vt_oss_key_cover) AS 
			                    vt_cover_url, vt_cdate FROM v_top_table WHERE vt_mid = ?",[$nVMid]);
			if ( $arrQ )
			{
				//...
				$nHSet	= Redis::hset
				(
					$sKey,
					'vt_mid',               $nVMid,
					'vt_song_name',		$arrQ[ 0 ]->vt_song_name,
					'vt_song_artist',	$arrQ[ 0 ]->vt_song_artist,
					'vt_song_album',	$arrQ[ 0 ]->vt_song_album,
					'vt_video_url',		$arrQ[ 0 ]->vt_video_url,
					'vt_cover_url',		$arrQ[ 0 ]->vt_cover_url,
					'vt_count_play',	$nPlay,
					'vt_count_set',		$nSet,
					'vt_count_download',	$nDownload,
					'vt_cdate',             $arrQ[ 0 ]->vt_cdate
				);
			}
		}

		//...
		$nExpireSeconds	= 60 * 60 * 24;
		$nSetExpire	= Redis::expire( $sKey, $nExpireSeconds );

		if (1 == $nHSet && 1 == $nSetExpire )
		{
			$nRet = CConst::ERROR_SUCCESS;
		}
		else
		{
			$nRet = CErrCodeLib::REDISLIB_ERROR_SETVTOPSETCONTENT_WRITE_NOW_KEY;
		}

		//	...
		return $nRet;



	}


	/***
	 *	@param $nVMid
	 *	@return int
	 */
	public static function _setVTopHashContent( $nVMid )
	{
		if ( ! CMIdLib::isValidMId( $nVMid ) )
		{
			return CErrCodeLib::REDISLIB_ERROR_SETVTOPHASHCONTENT_PARAM_V_MID;
		}

		//	...
		$nRet	= CErrCodeLib::REDISLIB_ERROR_SETVTOPHASHCONTENT_FAILED;

		//	...
		$sVMIdKey	= sprintf( "v-top-mid-%d", $nVMid );

		//	...
		$nExpireInSeconds	= 60 * 60 * 24;
		$sNowMinute		= date("YmdHi", time() );
		$nNowTimestamp		= strtotime( $sNowMinute );
		$nStartTimestamp	= $nNowTimestamp - $nExpireInSeconds;

		//
		//	searching ...
		//	delete the existing item if we find it
		//
		for ( $i = 0; $i < ( 24 * 60 ); $i ++ )
		{
			$nTimestamp	= $nStartTimestamp + ( $i * 60 );
			$sTime		= date( "YmdHi", $nTimestamp );
			$sIndexKey	= sprintf( "v-top-index-%d", $sTime );

			//	...
			$sValue		= Redis::hget( $sIndexKey, $sVMIdKey );

			if ( CLib::IsExistingString( $sValue ) && '1' == $sValue )
			{
				$nHDel	= Redis::hdel( $sIndexKey, $sVMIdKey );
				break;
			}
		}

		//
		//	insert/move the item to now index in minute
		//
		$sNowIndexKey	= sprintf( "v-top-index-%d", $sNowMinute );
		$nHSet		= Redis::hset( $sNowIndexKey, $sVMIdKey, 1 );
		$nSetExpire	= Redis::expire( $sNowIndexKey, $nExpireInSeconds );

		if ( 1 == $nHSet && 1 == $nSetExpire )
		{
			$nRet = CConst::ERROR_SUCCESS;
		}
		else
		{
			$nRet = CErrCodeLib::REDISLIB_ERROR_SETVTOPHASHCONTENT_WRITE_NOW_KEY;
		}

		//	...
		return $nRet;
	}


	//获取所有的指定前缀的key的值 并进行排序

	/***
	 *	@return array
	 */
	public static function getAllCacheKeys()
	{
		//	...
		$arrMid		= [];
		$nExpireInSeconds	= 60 * 60 * 24;
		$sNowMinute		= date("YmdHi", time() );
		$nNowTimestamp		= strtotime( $sNowMinute );
		$nStartTimestamp	= $nNowTimestamp - $nExpireInSeconds;
		//
		//	searching ...
		//	delete the existing item if we find it
		//
		for ( $i = 0; $i < ( 24 * 60 ); $i ++ )
		{
			$nTimestamp	= $nStartTimestamp + ( $i * 60 );
			$sTime		= date( "YmdHi", $nTimestamp );
			$sIndexKey	= sprintf( "v-top-index-%d", $sTime );

			//	...
			$arrValue	= Redis::hkeys( $sIndexKey );
			if( $arrValue )
			{
				$arrMid	 = $arrValue;
			}

		}


		//  ....

		$arrSum			= [];
		$arrKeyListReturn	= [];

		foreach ( $arrMid as $v )
		{

			$arrSum[ $v ]	= Redis::hget( $v, 'vt_count_play' )
				+ Redis::hget( $v, 'vt_count_set' )
				+ Redis::hget( $v, 'vt_count_download' );
		}

		arsort( $arrSum );
		foreach( $arrSum as $sVal )
		{
			$sKey	= array_search( $sVal, $arrSum );
			$arrKeyListReturn[] =
				[
					'vt_mid'		=> Redis::hget( $sKey, 'vt_mid'),
					'vt_song_name'		=> Redis::hget( $sKey, 'vt_song_name' ),
					'vt_song_artist'	=> Redis::hget( $sKey, 'vt_song_artist' ),
					'vt_song_album'		=> Redis::hget( $sKey, 'vt_song_album' ),
					'vt_video_url'		=> Redis::hget( $sKey, 'vt_video_url' ),
					'vt_cover_url'		=> Redis::hget( $sKey, 'vt_cover_url' ),
					'vt_count_play'		=> Redis::hget( $sKey, 'vt_count_play' ),
					'vt_count_set'		=> Redis::hget( $sKey, 'vt_count_set' ),
					'vt_count_download'	=> Redis::hget( $sKey, 'vt_count_download' ),
					'vt_cdate'		=> Redis::hget( $sKey, 'vt_cdate')
				];
		}
		return $arrKeyListReturn;
	}




}