<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;


class RouteServiceProvider extends ServiceProvider
{
	/**
	 *	This namespace is applied to your controller routes.
	 *
	 *	In addition, it is set as the URL generator's root namespace.
	 *
	 *	@var string
	 */
	protected $namespace 		= 'App\Http\Controllers';
	protected $m_sNamespaceWeb	= 'App\Http\Controllers\Web';
	protected $m_sNamespaceApi	= 'App\Http\Controllers\Api';
	protected $m_sNamespaceRd	= 'App\Http\Controllers\Rd';

	/**
	 *	Define your route model bindings, pattern filters, etc.
	 *
	 *	@return void
	 */
	public function boot()
	{
		//Route::pattern( 'id', '[0-9]+' );
		parent::boot();
	}

	/**
	 *	Define the routes for the application.
	 *
	 *	@return void
	 */
	public function map()
	{
		$this->mapWebRoutes();
		$this->mapApiRoutes();
		$this->mapRdRoutes();
	}

	/**
	 *	Define the "web" routes for the application.
	 *
	 *	These routes all receive session state, CSRF protection, etc.
	 *
	 *	@return void
	 */
	protected function mapWebRoutes()
	{
		Route::middleware( 'web' )
			->namespace( $this->m_sNamespaceWeb )
			->group( base_path( 'routes/web.php' ) );
	}

	/**
	 *	Define the "api" routes for the application.
	 *
	 *	These routes are typically stateless.
	 *
	 *	@return void
	 */
	protected function mapApiRoutes()
	{
		Route::middleware( 'api' )
			->namespace( $this->m_sNamespaceApi )
			->group( base_path( 'routes/api.php' ) );
	}

	/**
	 *	Define the "rd" routes for the application.
	 *
	 *	These routes are typically stateless.
	 *
	 *	@return void
	 */
	protected function mapRdRoutes()
	{
		Route::middleware( 'rd' )
			->namespace( $this->m_sNamespaceRd )
			->group( base_path( 'routes/rd.php' ) );
	}
}
