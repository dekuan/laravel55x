<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Dispatchers\Api\ApiUserDispatcher;


/**
 *	Class ApiUserController
 *	@package App\Http\Controllers\Api
 */
class ApiUserController extends Controller
{
	function __construct()
	{
		parent::__construct();

		//	...
		$this->_setClassesList([
			'1.0'	=> ApiUserDispatcher\ApiUserDispatcher_V1_0::class,
		]);
	}

}