<?php

namespace App\Http\Middleware\Api;

use Closure;
Use App\Http\Filters\CRoleFilter;

use App\Http\Constants\CErrCodeMiddleware;

use App\Http\Lib\NetworkLib;
use App\Http\Lib\LangLib;

/**
 *	Created by Laravel.
 *	User: xing
 *	Date: 12:10 PM October 27, 2017
 */
class ApiCheckRole
{
	/**
	 *	Handle an incoming request.
	 *
	 *	@param  \Illuminate\Http\Request	$oRequest
	 *	@param  \Closure			$oNext
	 *	@param  integer				$nRequestRole
	 *	@return mixed
	 */
	public function handle( $oRequest, Closure $oNext, $nRequestRole )
	{
		if ( 0 == strcasecmp( 'OPTIONS', $oRequest->getMethod() ) )
		{
			return $oNext( $oRequest );
		}

		if ( CRoleFilter::isValidRole( $nRequestRole ) )
		{
			return $oNext( $oRequest );
		}
		else
		{
			$nErrorId	= CErrCodeMiddleware::APICHECKRIGHTS_ERROR_PERMISSION;
			$sErrorDesc	= LangLib::getErrorDesc( 'api', '_middleware', $nErrorId );
			return NetworkLib::responseVData( $nErrorId, $sErrorDesc );
		}
	}
}
