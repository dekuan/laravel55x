<?php

namespace Tests;


/**
 *	Class PrintMdDocs
 *	@package Tests
 */
class PrintMdDocs
{
	public static function docPrintHeader( $arrPrintTitleHeader, $sFileName, & $bIsNewReturn )
	{
		$sFileContent = self::_getHeaderList($arrPrintTitleHeader);
		if ( is_string( $sFileContent ) && strlen( $sFileContent ) > 0 )
		{
			$cha		= mb_detect_encoding( $sFileContent );
			$sFileContent	= mb_convert_encoding( $sFileContent, $cha, "utf-8" );

			print_r( $sFileContent );

			//	写入文件
			if ( ! $bIsNewReturn )	//	追加
			{
				file_put_contents( $sFileName, "\xEF\xBB\xBF" . $sFileContent,FILE_APPEND );
			}
			else
			{
				file_put_contents( $sFileName,"\xEF\xBB\xBF" );
				$bIsNewReturn = false;
				$dump = new DumpDocs();
				$dump->testDumpDocumentation( $bIsNewReturn );
			}
		}
	}

	public static function docPrint( $strPrintTitleHeader, $arrTitle, $arrValue, $sFileName = 'doc/PrintMdDocs.md' ,& $is_new )
	{
		if ( is_array( $arrTitle ) && count( $arrTitle ) > 0
			&& is_array( $arrValue ) && count( $arrValue ) > 0
			&& is_string( $sFileName ) && strlen( $sFileName ) > 0
		)
		{
			$sFileContent	= '';

			//	获取header
			$sHeader	= self::_getHeader( $strPrintTitleHeader );
			$sFileContent	.= $sHeader;

			//	获得表头
			$sTitle		= self::_getTitle( $arrTitle );
			$sFileContent	.= $sTitle;

			//	获得表格格式
			$sFormat	= self::_getTableFormat( $arrTitle );
			$sFileContent	.= $sFormat;

			//	获得表格内容
			foreach( $arrValue as $arrRow )
			{
				$sRow = self::_getValue( $arrTitle, $arrRow );
				$sFileContent .= $sRow;
			}

			if ( is_string( $sFileContent ) && strlen( $sFileContent ) > 0 )
			{
				$cha		= mb_detect_encoding( $sFileContent );
				$sFileContent	= mb_convert_encoding( $sFileContent, $cha, "utf-8" );

				print_r( $sFileContent );
				
				//	写入文件
				if ( ! $is_new )	//	追加
				{
					file_put_contents( $sFileName, "\xEF\xBB\xBF" . $sFileContent,FILE_APPEND );
				}
				else
				{
					file_put_contents( $sFileName,"\xEF\xBB\xBF" );
					$is_new = false;
					$dump = new DumpDocs();
					$dump->testDumpDocumentation( $is_new );
				}
			}
		}
	}

	private static function _getHeaderList($arrPrintTitleHeader)
	{
		$sRtn = '';
		foreach ( $arrPrintTitleHeader as $value )
		{
			$sRtn .= "\n\r## [".$value . "]\n\r";
		}
		$sRtn .= "\n\r";
		
		return $sRtn;
	}

	private static function _getHeader($strPrintTitleHeader)
	{
		$sRtn = '';

		$sRtn .= "\n\r### [".$strPrintTitleHeader . "]\n\r";
		return $sRtn;
	}

	private static function _getTitle( $arrTitle )
	{
		$sRtn = '';

		if ( is_array( $arrTitle ) && count( $arrTitle ) > 0 )
		{
			foreach( $arrTitle as $sTitle => $sKey )
			{
				$sRtn .= self::_filterValue( $sKey ) . ' | ';
			}

			$sRtn = '| ' . $sRtn . "\n";
		}

		return $sRtn;
	}

	private static function _getTableFormat( $arrTitle )
	{
		$sRtn = '';

		if ( is_array( $arrTitle ) && count( $arrTitle ) > 0 )
		{
			for( $i = 0; $i < count( $arrTitle ); $i ++ )
			{
				if ( 0 == $i )
				{
					//	第一个左对齐
					$sRtn = '|:---';
				}
				else
				{
					//	其余居中对齐
					$sRtn .= '|:---:';
				}
			}

			$sRtn .= '|' . "\n";
		}

		return $sRtn;
	}

	private static function _getValue( $arrTitle, $arrValue )
	{
		$sRtn = '';

		if ( is_array( $arrTitle ) && count( $arrTitle ) > 0
			&& is_array( $arrValue ) && count( $arrValue ) > 0
		)
		{
			foreach ( $arrTitle as $sKey )
			{
				$sValue = '';
				if ( array_key_exists( $sKey, $arrValue ) )
				{
					$sValue = strval( $arrValue[ $sKey ] );
					$sValue = self::_filterValue( $sValue );
				}

				$sRtn .= $sValue . ' | ';
			}

			$sRtn = '| ' . $sRtn . "\n";
		}

		return $sRtn;
	}

	private static function _filterValue( $sValue )
	{
		if ( is_string( $sValue ) && strlen( $sValue ) > 0 )
		{
			$sValue = str_replace( '_', '_', $sValue );
		}

		return $sValue;
	}
}