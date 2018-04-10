<?php

namespace App\Http\Models\DataHub;

use App\Http\Lib\CacheLib;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

use dekuan\delib\CLib;
use dekuan\delib\CMIdLib;



/**
 *	Class CDataHubUser
 *	@package App\Http\Models\DataHub
 */
class CDataHubUser extends CCoreDataHubUser
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
	}


	/**
	 *	@param	string	$nUMId
	 *	@return null
	 */
	public function getUserInfoWithCache( $nUMId )
	{
		if ( ! CMIdLib::isValidMId( $nUMId ) )
		{
			return null;
		}

		//	...
		$nRet		= null;
		$nUMId		= intval( $nUMId );
		$sCacheKey	= CacheLib::getCacheKey( sprintf("user_table-info-mid-%d", $nUMId ) );
		$nCacheMinutes	= 30;		//	in minutes
		$nRet		= intval( Cache::store('redis')->remember
		(
			$sCacheKey,
			$nCacheMinutes,
			function() use ( $nUMId )
			{
				return $this->coreGetUserInfo( $nUMId );
			}
		) );

		return $nRet;
	}

}