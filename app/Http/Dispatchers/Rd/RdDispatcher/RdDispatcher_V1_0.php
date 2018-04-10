<?php

namespace App\Http\Dispatchers\Rd\RdVideoDispatcher;


use Illuminate\Support\Facades\Input;

use App\Http\Constants\CErrCodeDispatcher;
use App\Http\Dispatchers\Dispatcher;
use dekuan\vdata\CConst;

use dekuan\delib\CLib;
use dekuan\delib\CMIdLib;



/***
 *	Class RdDispatcher_V1_0
 *	@package App\Http\Dispatchers\Rd\RdVideoDispatcher
 */
class RdDispatcher_V1_0 extends Dispatcher
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
	}


	/**
	 *	@return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|int
	 */
	public function redirectDownload()
	{
		$nMId		= Input::get( 'mid' );
		$sDownloadUrl	= '';

		//	...
		$nErrorId 	= CErrCodeDispatcher::RDVIDEODISPATCHER_ERROR_REDIRECTDOWNLOAD_FAILED;

		if ( CMIdLib::isValidMId( $nMId ) )
		{
			$nErrorId	= CConst::ERROR_SUCCESS;
			$sDownloadUrl	= 'http://www.dekuan.org/file.dmg';
		}
		else
		{
			$nErrorId	= CErrCodeDispatcher::RDVIDEODISPATCHER_ERROR_REDIRECTDOWNLOAD_PARAM_MID;
		}

		if ( CConst::ERROR_SUCCESS == $nErrorId )
		{
			return redirect( $sDownloadUrl );
		}
		else
		{
			return $nErrorId;
		}

	}

}