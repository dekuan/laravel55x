<?php

use App\Http\Constants\CErrCodeServices;
use dekuan\deobjectstorage\CDeObjectStorageErrCode;


/**
 *	Created by PhpStorm.
 *	User: xing
 *	Date: 6:53 PM October 27, 2017
 */
return [

	//
	//	app/Http/Middleware/Api/ApiCheckLogin.php
	//
	CErrCodeServices::APICHECKLOGIN_ERROR_NOT_LOGGEDIN		=> '请先登录系统',
	CErrCodeServices::APICHECKLOGIN_ERROR_PERMISSION		=> '登录系统检测到您无权访问',


	//
	//	app/Http/Middleware/Api/ApiCheckRole.php
	//
	CErrCodeServices::APICHECKRIGHTS_ERROR_PERMISSION		=> '权限系统检测到您无权访问',


	//
	//	app/Http/Middleware/Api/ApiCheckGroup.php
	//
	CErrCodeServices::APICHECKGROUP_ERROR_PARAMETER			=> '参数错误，指定信息不存在',


	//
	//	app/Http/Middleware/Api/ApiCheckSuper.php
	//	
	CErrCodeServices::APICHECKSUPER_ERROR_NOT_LOGGEDIN		=> '请先登录系统',
	CErrCodeServices::APICHECKSUPER_ERROR_PERMISSION		=> '登录系统检测到您无权访问',
	CErrCodeServices::APICHECKSUPER_ERROR_SUPERADMIN		=> '登录系统检测到您无权访问(S)',

];