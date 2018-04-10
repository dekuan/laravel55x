<?php

namespace App\Http\Lib;

use Illuminate\Support\Facades\Log;

use dekuan\delib\CLib;

/**
 * Created by PhpStorm.
 * User: xing
 * Date: 23/04/2017
 * Time: 12:11 AM
 */
class LogLib
{
	//
	//	write log to file
	//
	static function writeSysLog( $sFunctionName, $arrInfo )
	{
		return self::writeFileLog( 'sys', $sFunctionName, $arrInfo );
	}
	static function writeCronLog( $sFunctionName, $arrInfo )
	{
		return self::writeFileLog( 'cron', $sFunctionName, $arrInfo );
	}

	static function writeFileLog( $sLogType, $sFunctionName, $arrInfo )
	{
		//
		//	sFunctionName	- [in] string/null	function name
		//	arrInfo		- [in] array		data to be wrote into log in array format
		//	RETURN		- false / true
		//
		if ( ! CLib::IsArrayWithKeys( $arrInfo ) )
		{
			return false;
		}

		$bRecordFileLog	= config( 'xc.record_file_log', false );
		$sFilePath	= sprintf(
			"%s/logs/%s_%s.log",
			storage_path(),
			( CLib::IsExistingString( $sLogType ) ? $sLogType : 'sys' ),
			date( 'Y-m-d', time() ) );
		$sFunctionName	= ( CLib::IsExistingString( $sFunctionName ) ? $sFunctionName : 'info' );

		if ( $bRecordFileLog )
		{
			Log::useFiles( $sFilePath );
			Log::info( $sFunctionName, $arrInfo );
		}
	}

}