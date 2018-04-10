<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Dispatchers\Web\WebHomepageDispatcher;


/**
 *	Class WebHomepageController
 *	@package App\Http\Controllers\Web
 */
class WebHomepageController extends Controller
{
	function __construct()
	{
		parent::__construct();

		//	...
		$this->m_arrData[ 'homepage' ] = true;

		//	...
		$this->_setClassesList([
			'1.0'	=> WebHomepageDispatcher\WebHomepageDispatcher_V1_0::class,
		]);
	}
}