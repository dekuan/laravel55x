<?php

namespace App\Http\Constants;


class CErrCodeDispatcher extends CErrCode
{
	//
	//	Http/Dispatchers/Api/ApiContactsDispatcher/ApiContactsDispatcher_V1_0.php
	//
	const APICONTACTSDISPATCHER_ERROR_POSTAPIMEMBERCONTACTSSYNC_PARAM_PN			= self::ERROR_DISPATCHER_START +  7000; //      ...
	const APICONTACTSDISPATCHER_ERROR_POSTAPIMEMBERCONTACTSSYNC_FAILED			= self::ERROR_DISPATCHER_START +  7010; //      ...
	const APICONTACTSDISPATCHER_ERROR_POSTAPIMEMBERCONTACTSSYNC_SYNCCONTACTS		= self::ERROR_DISPATCHER_START +  7020; //      ...


}