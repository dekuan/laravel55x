<?php

namespace App\Http\Models\DataHub;

use App\Http\Constants\CErrCodeModelDataHub;
use App\Http\Models\CDataHubBase;
use dekuan\delib\CLib;
use dekuan\delib\CMIdLib;



/**
 *	Class CCoreDataHubUser
 *	@package App\Http\Models\DataHub
 */
class CCoreDataHubUser extends CDataHubBase
{
	public function __construct()
	{
		parent::__construct();

		//	...
		$this->m_sTable		= 'user_table';
		$this->m_sFieldStatus	= 'u_status';
		$this->m_sFieldId	= 'u_id';
		$this->m_sFieldMId	= 'u_mid';
	}
	public function __destruct()
	{
	}


	/**
	 *	@param	$nUMId
	 *	@return null|object
	 *	@throws	\App\Http\Exceptions\RuntimeException
	 */
	public function coreGetUserInfo( $nUMId )
	{
		if ( ! CMIdLib::isValidMId( $nUMId ) )
		{
			return null;
		}

		//	...
		$objRet		= null;
		$objUserInfo	= $this->getTableInfo( $nUMId );
		if ( CLib::IsObjectWithProperties( $objUserInfo ) )
		{
			$objRet	= $objUserInfo;
		}

		return $objRet;
	}


}