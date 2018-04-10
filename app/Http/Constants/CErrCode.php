<?php

namespace App\Http\Constants;

use dekuan\vdata\CConst;


/***
 *	Class CErrCode
 *	@package App\Http\Constants
 */
class CErrCode extends CConst
{
	//
	//	API
	//	error code range:
	//	1000001 ~ 2000000
	//
	const ERROR_BASE			= 1000000;

	
	//
	//	app/Http/Controller
	//	10000
	//
	const ERROR_CONTROLLER_START		= self::ERROR_BASE + 0;
	const ERROR_CONTROLLER_END		= self::ERROR_BASE + 9999;


	//
	//	app/Http/Services
	//	30000
	//
	const ERROR_SERVICE_START		= self::ERROR_BASE + 10000;
	const ERROR_SERVICE_END			= self::ERROR_BASE + 39999;


	//
	//	app/Http/Dispatchers
	//	30000
	//
	const ERROR_DISPATCHER_START		= self::ERROR_BASE + 40000;
	const ERROR_DISPATCHER_END		= self::ERROR_BASE + 69999;


	//
	//	app/Http/Libs
	//	30000
	//
	const ERROR_LIB_START			= self::ERROR_BASE + 70000;
	const ERROR_LIB_END			= self::ERROR_BASE + 99999;

	
	//
	//	app/Http/Middleware
	//	30000
	//
	const ERROR_MIDDLEWARE_START		= self::ERROR_BASE + 100000;
	const ERROR_MIDDLEWARE_END		= self::ERROR_BASE + 129999;



	//
	//	app/Http/Models/DataHub
	//	80000
	//
	const ERROR_MODEL_DATAHUB_START		= self::ERROR_BASE + 130000;
	const ERROR_MODEL_DATAHUB_END		= self::ERROR_BASE + 199999;




}