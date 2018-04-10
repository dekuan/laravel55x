<?php

namespace App\Http\Services\Templates;


class CTemplateMail extends CTemplate
{
	public function __construct()
	{
	}
	public function __destruct()
	{
	}

	public function GetContentVerification( $sMessage )
	{
		if ( ! is_string( $sMessage ) || 0 == strlen( $sMessage ) )
		{
			return '';
		}
		return $this->GetRender( 'templates.mails.verification', [ 'message' => $sMessage ] );
	}
}