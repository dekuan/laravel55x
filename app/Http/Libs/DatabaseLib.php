<?php

namespace App\Http\Lib;

use dekuan\delib\CLib;


class DatabaseLib
{
	/**
	 *	@param	int	$nMId
	 *	@return int
	 */
	static function getHashTableId( $nMId )
	{
		$nMId = intval( $nMId );
		return abs( crc32( strval( $nMId ) ) ) % 100;
	}

	/**
	 *	@param	$arrRecordList	array
	 *	@param	$arrRemain	array
	 *	@return array 
	 */
	static function filterRecordList( Array $arrRecordList, Array $arrRemain )
	{
		$arrRet	= null;

		if ( CLib::IsArrayWithKeys( $arrRecordList ) &&
			CLib::IsArrayWithKeys( $arrRemain ) )
		{
			$arrRet	= [];
			foreach ( $arrRecordList as $vKey => $vValue )
			{
				$arrRet[ $vKey ] = (object)( self::filterRecord( $vValue, $arrRemain ) );	
			}
		}

		return $arrRet;
	}
	
	/**
	 *	@param	$vRecord	mixed
	 *	@param	$arrRemain	array
	 *	@return	array 
	 */
	static function filterRecord( $vRecord, Array $arrRemain )
	{
		$arrRet	= null;

		if ( CLib::IsObjectWithProperties( $vRecord ) )
		{
			$vRecord = get_object_vars( $vRecord );
		}

		if ( CLib::IsArrayWithKeys( $vRecord ) &&
			CLib::IsArrayWithKeys( $arrRemain ) )
		{
			$arrRemain	= array_flip( $arrRemain );
			$arrRet		= array_filter( $vRecord, function( $sKey ) use ( $arrRemain )
				{
					return array_key_exists( $sKey, $arrRemain );
				},
				ARRAY_FILTER_USE_KEY
			);
		}
		
		return $arrRet;
	}
	
	
	/**
	 *	@param $arrFieldList	array
	 *	@return	string
	 */
	static function buildFieldName( Array $arrFieldList )
	{
		//
		//	arrFieldList	-
		//	[
		//		[ 'field1', 'json_key' ],
		//		[ 'field2', 'json_key' ],
		//		[ 'field3', 'json_key' ],
		//		...
		//	]
		//
		//	SELECT pref_settings->>"$.key1.keya111" FROM user_table;
		//
		$sRet		= '';
		$arrResult	= [];

		if ( CLib::IsArrayWithKeys( $arrFieldList ) )
		{
			foreach ( $arrFieldList as $arrField )
			{
				$arrTmp		= [];

				if ( CLib::IsArrayWithKeys( $arrField ) )
				{
					foreach ( $arrField as $sField )
					{
						$sField	= trim( $sField );
						if ( self::isValidFieldName( $sField ) )
						{
							$arrTmp[] = addslashes( $sField );
						}
						else
						{
							$arrTmp	= null;
							break;
						}
					}
				}

				if ( CLib::IsArrayWithKeys( $arrTmp ) )
				{
					$sWholeField	= array_shift( $arrTmp );
					if ( CLib::IsArrayWithKeys( $arrTmp ) )
					{
						$sWholeField = sprintf( "%s->>\"\$.%s\"", $sWholeField, implode( '.', $arrTmp ) );
					}

					//	...
					$arrResult[]	= $sWholeField;
				}
			}

			//	...
			$sRet	= implode( ', ', $arrResult );
		}

		return $sRet;
	}

	/**
	 *	@param	$sStr string
	 *	@return boolean 
	 */
	static function isValidFieldName( $sStr )
	{
		//
		//	sStr	- [in] string
		//	RETURN	- true / false
		//
		$bRet		= false;
		$sStdChars	= "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_";

		if ( CLib::IsExistingString( $sStr, true ) )
		{
			$sStr		= trim( $sStr );
			$nStrLength	= strlen( $sStr );

			if ( 0 === strncasecmp ( 'COUNT(*)', $sStr, 8 ) )
			{
				//
				//	COUNT(*) AS t_count
				//
				$bRet = true;
			}
			else
			{
				//
				//	something else ...
				//
				$nErrorCount	= 0;
				for ( $i = 0; $i < $nStrLength; $i ++ )
				{
					$cChr = substr( $sStr, $i, 1 );
					if ( ! strchr( $sStdChars, $cChr ) )
					{
						$nErrorCount ++;
						break;
					}
				}

				//	...
				$bRet = ( 0 == $nErrorCount ? true : false );	
			}
		}

		return $bRet;
	}
}
