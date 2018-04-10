<?php

namespace App\Http\Lib;

use Illuminate\Support\Facades\Lang;

use App\Http\Lib\DateTimeLib;

use dekuan\delib\CLib;


/**
 *	Created by PhpStorm.
 *	User: xing
 *	Date: 14/05/2017
 *	Time: 9:27 PM
 */
class LangLib
{
	static function getErrorDesc( $sRouteType, $sModel, $nErrorId )
	{
		if ( ! CLib::IsExistingString( $sRouteType ) )
		{
			return '';
		}
		if ( ! in_array( $sRouteType, [ 'api', 'web' ] ) )
		{
			return '';
		}
		if ( ! CLib::IsExistingString( $sModel ) )
		{
			return '';
		}
		if ( ! is_numeric( $nErrorId ) )
		{
			return '';
		}

		//	...
		$sRet	= '';
		$sKey	= sprintf( "error-%s-%s.%d", $sRouteType, $sModel, $nErrorId );

		if ( Lang::has( $sKey ) )
		{
			$sRet = Lang::get( $sKey );
		}
	
		return $sRet;
	}


}