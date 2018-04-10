<?php

namespace App\Http\Dispatchers;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Lib\CommonLib;
use App\Http\Lib\NetworkLib;



/**
 * Created by PhpStorm.
 * User: xing
 * Date: 07/04/2017
 * Time: 11:57 AM
 */
class Dispatcher
{
	protected static $g_arrInstances	= [];

	//	...
	protected $m_sDispatcherVersion		= '1.0';


	public function __construct()
	{
		$this->m_sDispatcherVersion = '1.0';
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

	final public function getDispatcherVersion()
	{
		return $this->m_sDispatcherVersion;
	}

	final public function setDispatcherVersion( $sVersion )
	{
		$this->m_sDispatcherVersion = $sVersion;
	}


	final static function __converterMIdToString( $arrData )
	{
		if ( is_object( $arrData ) )
		{
			$arrData = (Array)( $arrData );			
		}

		if ( is_array( $arrData ) && count( $arrData ) > 0 )
		{
			foreach ( $arrData as $vKey => $vValue )
			{
				if ( is_array( $vValue ) || is_object( $vValue ) )
				{
					$arrData[ $vKey ] = self::__converterMIdToString( $vValue );
				}
				else if ( is_string( $vKey ) && strstr( $vKey, '_mid' ) )
				{
					$arrData[ $vKey ] = strval( $vValue );
				}
			}
		}

		return $arrData;
	}
	
	/**
	 *	@param	$nErrorId	int
	 *	@param	$sErrorDesc	string
	 *	@param	$arrVData	array
 	 *	@return	\Symfony\Component\HttpFoundation\Response
	 */
	final public function sendResponse( $nErrorId, $sErrorDesc = '', $arrVData = [] )
	{
		return NetworkLib::responseVData( $nErrorId, $sErrorDesc, self::__converterMIdToString( $arrVData ), $this->m_sDispatcherVersion );
	}

}