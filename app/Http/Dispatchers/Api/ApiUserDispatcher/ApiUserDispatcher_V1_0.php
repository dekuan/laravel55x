<?php

namespace App\Http\Dispatchers\Api\ApiUserDispatcher;

use Illuminate\Support\Facades\Input;
use App\Http\Dispatchers\Dispatcher;
use App\Http\Lib\LangLib;

use App\Http\Lib\UserLib;
use App\Http\Models\DataHub\CDataHubUser;
use App\Http\Constants\CConstantX;

use dekuan\vdata\CConst;
use dekuan\delib\CLib;




/**
 *	Class ApiUserDispatcher_V1_0
 *	@package App\Http\Dispatchers\Api\ApiUserDispatcher
 */
class ApiUserDispatcher_V1_0 extends Dispatcher
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
	}


	/**
	 *	@return	\Symfony\Component\HttpFoundation\Response
	 */
	public function getApiMemberMain()
	{
		$nErrorId	= CConst::ERROR_SUCCESS;

		//	...
		$nUMId		= UserLib::getLoggedInUMId();
		$objUserInfo	= CDataHubUser::getInstance()->getUserInfoWithCache( $nUMId );

		return $this->sendResponse
		(
			$nErrorId,
			LangLib::getErrorDesc( 'api', '_dispatcher', $nErrorId ),
			[
				'uc_info'	=> UserLib::getUCInfo(),
				'user_info'	=> $objUserInfo,
			]
		);
	}
}