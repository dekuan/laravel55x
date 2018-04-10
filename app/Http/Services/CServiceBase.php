<?php

namespace App\Http\CServices;

use Illuminate\Support\Facades\Facade;


/**
 * Created by PhpStorm.
 * User: xing
 * Date: 19/04/2017
 * Time: 3:33 PM
 */
class CServiceBase
{
	protected static $g_arrInstances	= [];

	public function __construct()
	{
	}
	public function __destruct()
	{
	}

	final public static function getInstance()
	{
		$oRet		= null;
		$sClassName	= get_called_class();

		if ( false !== $sClassName )
		{
			if ( ! isset( self::$g_arrInstances[ $sClassName ] ) )
			{
				$oRet = self::$g_arrInstances[ $sClassName ] = new $sClassName();
			}
			else
			{
				$oRet = self::$g_arrInstances[ $sClassName ];
			}
		}

		return $oRet;
	}

	final private function __clone()
	{
	}
}