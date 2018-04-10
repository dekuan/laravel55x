<?php
namespace App\Http\Middleware;


use Closure;
use dekuan\vdata\CConst;
use dekuan\vdata\CResponse;

class PreflightResponse
{
	/**
	 *	Handle an incoming request.
	 *
	 *	@param \Illuminate\Http\Request $request
	 *	@param \Closure $next
	 *	@return mixed
	 */
	public function handle( $request, Closure $next )
	{
		if ( "OPTIONS" === $request->getMethod() )
		{
			$cResponse = CResponse::GetInstance();
			$cResponse->SetCorsDomains
			([
				'30mai.cn',
				'kulaidian.com',
				'.kulaidian.com',
				'www.30mai.cn',
				'api.call.30mai.cn',
				'ui.call.30mai.cn',
			]);
			return $cResponse->GetVDataResponse( CConst::ERROR_SUCCESS, 'Pre-flight Response', [] );
		}

		return $next( $request );
	}
}