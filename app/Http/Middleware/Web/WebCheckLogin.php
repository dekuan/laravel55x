<?php

namespace App\Http\Middleware\Web;

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
class WebCheckLogin
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
		if ( SafeLib::isSafeCallByIP() )
		{
			if ( UserLib::isLoggedIn() &&
				CMIdLib::isValidMId( UserLib::getLoggedInUMId() ) )
			{
				return $oNext( $oRequest );
			}
			else
			{
				return NetworkLib::redirectToSignInPage();
			}
		}
		else
		{
			return 'Access Denied!';
		}
	}
}
