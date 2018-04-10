<?php

namespace App\Http\Services\Templates;

use dekuan\delib\CLib;

class CTemplate
{
	public function __construct()
	{
	}
	public function __destruct()
	{
	}

	//
	//	@ public
	//	Get rendered string contents of the template with data
	//
	public function GetRender( $sPath, $arrData = [] )
	{
		if ( ! CLib::IsExistingString( $sPath ) )
		{
			return '';
		}

		//	...
		$sRet = '';

		if ( view()->exists( $sPath ) )
		{
			$sRet = view( $sPath, ( is_array( $arrData ) ? $arrData : [] ) )->render();
		}

		return $sRet;
	}
}