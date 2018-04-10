<?php

use App\Http\Constants\CErrCodeMiddleware;


/**
 *	Created by PhpStorm.
 *	User: xing
 *	Date: 6:53 PM October 27, 2017
 */
return [

	//
	//	app/Http/Middleware/Api/ApiCheckCSRF.php
	//
	CErrCodeMiddleware::APIANTICSRF_INVALID_TOKEN			=> 'Access denied for invalid csrf token.',


	//
	//	app/Http/Middleware/Api/ApiCheckLogin.php
	//
	CErrCodeMiddleware::APICHECKLOGIN_ERROR_NOT_LOGGEDIN		=> '请先登录系统',
	CErrCodeMiddleware::APICHECKLOGIN_ERROR_PERMISSION		=> '登录系统检测到您无权访问',


	//
	//	app/Http/Middleware/Api/ApiCheckRole.php
	//
	CErrCodeMiddleware::APICHECKRIGHTS_ERROR_PERMISSION		=> '权限系统检测到您无权访问',


	//
	//	app/Http/Middleware/Api/ApiCheckGroup.php
	//
	CErrCodeMiddleware::APICHECKGROUP_ERROR_PARAMETER		=> '参数错误，指定信息不存在',


	//
	//	app/Http/Middleware/Api/ApiCheckSuper.php
	//	
	CErrCodeMiddleware::APICHECKSUPER_ERROR_NOT_LOGGEDIN		=> '请先登录系统',
	CErrCodeMiddleware::APICHECKSUPER_ERROR_PERMISSION		=> '登录系统检测到您无权访问',
	CErrCodeMiddleware::APICHECKSUPER_ERROR_SUPERADMIN		=> '登录系统检测到您无权访问(S)',

];