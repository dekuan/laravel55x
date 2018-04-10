<?php

namespace App\Http\Middleware\Web;

use Closure;
Use App\Http\Filters\CRoleFilter;


/**
 *	Created by Laravel.
 *	User: xing
 *	Date: 19:10 PM October 27, 2017
 */
class WebCheckRights
{
	/**
	 *	Handle an incoming request.
	 *
	 *	@param  \Illuminate\Http\Request	$request
	 *	@param  \Closure			$next
	 *	@param  integer				$nRequestRole
	 *	@return mixed
	 */
	public function handle( $request, Closure $next, $nRequestRole )
	{
		$nRequestRole	= intval( $nRequestRole );

		if ( CRoleFilter::isValidRole( $nRequestRole ) )
		{
			return $next( $request );
		}
		else
		{
			return redirect( '/web/error/permission' );
		}
	}
}