<?php

namespace App\Http\Constants;

class CErrCodeMiddleware extends CErrCode
{
	//
	//	app/Http/Middleware/Api/ApiCheckCSRF.php
	//
	const APIANTICSRF_INVALID_TOKEN			= self::ERROR_MIDDLEWARE_START + 1;	//	...
	

	//
	//	app/Http/Middleware/Api/ApiCheckLogin.php
	//
	const APICHECKLOGIN_ERROR_NOT_LOGGEDIN		= self::ERROR_MIDDLEWARE_START + 50;	//	...
	const APICHECKLOGIN_ERROR_PERMISSION		= self::ERROR_MIDDLEWARE_START + 51;	//	...


	//
	//	app/Http/Middleware/Api/ApiCheckRole.php
	//
	const APICHECKRIGHTS_ERROR_PERMISSION		= self::ERROR_MIDDLEWARE_START + 100;	//	...
	

	//
	//	app/Http/Middleware/Api/ApiCheckGroup.php
	//
	const APICHECKGROUP_ERROR_PARAMETER		= self::ERROR_MIDDLEWARE_START + 150;	//	...


	//
	//	app/Http/Middleware/Api/ApiCheckSuper.php
	//
	const APICHECKSUPER_ERROR_NOT_LOGGEDIN		= self::ERROR_MIDDLEWARE_START + 200;	//	...
	const APICHECKSUPER_ERROR_PERMISSION		= self::ERROR_MIDDLEWARE_START + 201;	//	...
	const APICHECKSUPER_ERROR_SUPERADMIN		= self::ERROR_MIDDLEWARE_START + 202;	//	...

}