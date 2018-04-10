<?php

namespace App\Http\Controllers\Rd;

use App\Http\Controllers\Controller;
use App\Http\Dispatchers\Rd\RdVideoDispatcher;


/**
 *	Class RdController
 *	@package App\Http\Controllers\Rd
 */
class RdController extends Controller
{
	function __construct()
	{
		parent::__construct();

		//	...
		$this->_setClassesList([
			'1.0' => RdVideoDispatcher\RdDispatcher_V1_0::class
		]);
	}
}