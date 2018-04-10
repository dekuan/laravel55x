<?php

namespace App\Http\Lib;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

use App\Http\Middleware\Api\ApiCheckCSRF;

use dekuan\delib\CLib;


class SafeLib
{
	static function getCSRFToken()
	{
		return ApiCheckCSRF::getInstance()->getToken();
	}

	static function isSafeCallByIP()
	{
		return true;
	}

	//
	//	根据 IP 参数检查: 是否允许用户访问
	//
	static function isAllowByIp( $sCacheSubKey, $nCacheMinute, $nMaxTimes, & $sCacheKey = '' )
	{
		//
		//	sCacheSubKey	- [in] string	sub cache key
		//	nCacheMinute	- [in] int	cached minute
		//	nMaxTimes	- [in] int	max times
		//	sCacheKey	- [out] string	cache key
		//	RETURN		- true / false
		//
		if ( ! CLib::IsExistingString( $sCacheSubKey ) )
		{
			return false;
		}
		if ( ! is_numeric( $nCacheMinute ) )
		{
			return false;
		}
		if ( ! is_numeric( $nMaxTimes ) )
		{
			return false;
		}

		//	...
		$bRet = false;
		$sCacheKey = self::getCacheKeyWithIp( $sCacheSubKey );

		if ( CLib::IsExistingString( $sCacheKey ) )
		{
			$nCacheTimes = Cache::store( 'redis' )->get( $sCacheKey );
			if ( is_numeric( $nCacheTimes ) )
			{
				//
				//	redis 中存在数据, 同一个 IP 地址, n 分钟内只能访问 x 次. 1440 = 60 * 24
				//
				if ( $nCacheTimes < $nMaxTimes )
				{
					//
					//	规定时间内的访问次数 小于 最大值
					//
					$bRet = true;
				}

				//	...
				Cache::store( 'redis' )->increment( $sCacheKey, 1 );
			}
			else
			{
				//
				//	仅仅允许本次创建, 下一次需要在缓存消失以后
				//
				$bRet = true;

				//
				//	记录访问次数
				//
				$nExpires = Carbon::now()->addMinutes( $nCacheMinute );
				Cache::store( 'redis' )->put( $sCacheKey, 1, $nExpires );
			}
		}

		return $bRet;
	}


	static function getCacheKeyWithIp( $sSubKey )
	{
		if ( ! CLib::IsExistingString( $sSubKey ) )
		{
			return '';
		}

		//	...
		$sClientIP	= CLib::GetClientIP( false );
		$sSubKey	= sprintf( "%s-%s", $sSubKey, ( CLib::IsExistingString( $sClientIP ) ? $sClientIP : '' ) );
		return CacheLib::getCacheKey( $sSubKey, '', false );
	}
}