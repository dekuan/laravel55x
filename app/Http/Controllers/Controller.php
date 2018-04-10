<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Input;

use App\Http\Lib\NetworkLib;
use App\Http\Lib\ConfigLib;
use App\Http\Lib\SafeLib;
use App\Http\Lib\UserLib;

use dekuan\vdata\CRemote;
use dekuan\delib\CLib;
use dekuan\delib\CEnv;


/**
 *	Class Controller
 *	@package App\Http\Controllers
 */
class Controller extends BaseController
{
	use AuthorizesRequests;
	use DispatchesJobs;
	use ValidatesRequests;



	//	...
	protected static $g_arrInstancesList;

	protected $m_sAcceptedVersion;
	protected $m_arrFullClassNameList;
	protected $m_arrParam;
	protected $m_arrData;
	protected $m_sScheme;
	protected $m_sHostName;

	function __construct()
	{
		self::$g_arrInstancesList	= [];

		$this->m_sAcceptedVersion	= CRemote::GetAcceptedVersionEx();
		$this->m_arrFullClassNameList	= null;
		$this->m_sScheme		= NetworkLib::getSafeScheme();
		$this->m_sHostName		= NetworkLib::getSafeHostName();
		$this->m_arrData		=
			[
				'title'			=> 'Laravel 5.5x',
				'domain'		=> ConfigLib::getHostCookie()
			];

		//	...
		$this->m_arrData[ 'user_info' ]			= new \stdClass();
		$this->m_arrData[ 'user_info' ]->u_mid		= UserLib::getLoggedInUMId();
		$this->m_arrData[ 'user_info' ]->u_nickname	= UserLib::getLoggedInUNickname();
		$this->m_arrData[ 'user_info' ]->avatar_url	= NetworkLib::getAvatarUrl( UserLib::getLoggedInUMId() );
	}

	protected function _setClassesList( $arrList )
	{
		$this->m_arrFullClassNameList = $arrList;
	}
	protected function _createInstanceByVersion()
	{
		$oRet = null;

		if ( CLib::IsExistingString( $this->m_sAcceptedVersion ) &&
			CLib::IsArrayWithKeys( $this->m_arrFullClassNameList, $this->m_sAcceptedVersion ) )
		{
			$oRet = $this->m_arrFullClassNameList[ $this->m_sAcceptedVersion ];
		}
		else if ( CLib::IsArrayWithKeys( $this->m_arrFullClassNameList ) )
		{
			//
			//	mixed reset ( array &$array )
			//		Returns the value of the first array element, or FALSE if the array is empty.
			//
			$oDefaultCls	= reset( $this->m_arrFullClassNameList );
			if ( $oDefaultCls )
			{
				$oRet = $oDefaultCls;
			}
		}

		return $this->_GetInstanceByFullClassName( $oRet );
	}


	private function _GetInstanceByFullClassName( $sFullClassName )
	{
		$oRet		= null;

		if ( CLib::IsExistingString( $sFullClassName ) )
		{
			if ( ! isset( self::$g_arrInstancesList[ $sFullClassName ] ) )
			{
				$oRet = self::$g_arrInstancesList[ $sFullClassName ] = new $sFullClassName();
			}
			else
			{
				$oRet = self::$g_arrInstancesList[ $sFullClassName ];
			}
		}

		return $oRet;
	}


	/**
	 *	@param string	$sFunctionName
	 *	@param array	$arrArguments
	 *	@return mixed
	 */
	public function __call( $sFunctionName, $arrArguments )
	{
		return $this->_createInstanceByVersion()->{$sFunctionName}( $this->m_arrData, $arrArguments );
	}

}