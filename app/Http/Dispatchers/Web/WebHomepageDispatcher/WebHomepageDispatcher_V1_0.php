<?php

namespace App\Http\Dispatchers\Web\WebHomepageDispatcher;

use App\Http\Dispatchers\Dispatcher;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Model;

use App\Http\Lib\ShareLib;
use App\Http\Models\DataHub;
use App\Http\Lib\UserLib;
use App\Http\Lib\NetworkLib;

use dekuan\delib\CLib;


/**
 *	Created by PhpStorm.
 *	User: xing
 *	Date: December 20, 2017
 */
class WebHomepageDispatcher_V1_0 extends Dispatcher
{
	public function getHomepage( $arrData )
	{
		return View( 'web-homepage', $arrData );
	}



}