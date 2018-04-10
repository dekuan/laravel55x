<?php

namespace App\Http\Middleware\Api;

use Closure;

use App\Http\Lib\NetworkLib;
use App\Http\Lib\SafeLib;
use App\Http\Lib\UserLib;
use App\Http\Lib\LangLib;

use App\Http\Constants\CErrCodeMiddleware;
use dekuan\delib\CMIdLib;


/**
 *	Created by Laravel.
 *	User: xing
 *	Date: 09:10 PM October 27, 2017
 */
class ApiCheckLogin
{
	/**
	 *	Handle an incoming request.
	 *
	 *	@param  \Illuminate\Http\Request  $oRequest
	 *	@param  \Closure  $oNext
	 *	@return mixed
	 */
	public function handle( $oRequest, Closure $oNext )
	{
		if ( 0 == strcasecmp( 'OPTIONS', $oRequest->getMethod() ) )
		{
			return $oNext( $oRequest );
		}
		
		if ( SafeLib::isSafeCallByIP() )
		{
			if ( UserLib::isLoggedIn() &&
				CMIdLib::isValidMId( UserLib::getLoggedInUMId() ) )
			{
				return $oNext( $oRequest );
			}
			else
			{
				$nErrorId	= CErrCodeMiddleware::APICHECKLOGIN_ERROR_NOT_LOGGEDIN;
				$sErrorDesc	= LangLib::getErrorDesc( 'api', '_middleware', $nErrorId );
				return NetworkLib::responseVData( $nErrorId, $sErrorDesc );
			}
		}
		else
		{
			$nErrorId	= CErrCodeMiddleware::APICHECKLOGIN_ERROR_PERMISSION;
			$sErrorDesc	= LangLib::getErrorDesc( 'api', '_middleware', $nErrorId );
			return NetworkLib::responseVData( $nErrorId, $sErrorDesc );
		}
	}
}
