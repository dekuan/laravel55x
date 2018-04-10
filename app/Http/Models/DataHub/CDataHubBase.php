<?php

namespace App\Http\Models;

use App\Http\Lib\DatabaseLib;
use dekuan\delib\CMIdLib;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\DB;

use App\Http\Exceptions\RuntimeException;

use App\Http\Lib\ParameterLib;

use dekuan\delib\CLib;
use dekuan\vdata\CConst;



/**
 *	class of CDataHubBase
 */
abstract class CDataHubBase extends Model
{
	private static $g_arrInstances	= [];

	protected $m_sTable		= null;
	protected $m_sFieldMId		= null;
	protected $m_sFieldId		= null;
	protected $m_sFieldStatus	= null;

	


	public function __construct( Array $arrAttributes = [] )
	{
		parent::__construct( $arrAttributes );
	}
	public function __destruct()
	{
	}
	
	
	/***
	 *	@return self|null
	 */
	final public static function getInstance()
	{
		$oRet		= null;
		$sClassName	= get_called_class();

		if ( false !== $sClassName )
		{
			if ( ! isset( self::$g_arrInstances[ $sClassName ] ) )
			{
				$oRet = self::$g_arrInstances[ $sClassName ] = new $sClassName();
			}
			else
			{
				$oRet = self::$g_arrInstances[ $sClassName ];
			}
		}

		return $oRet;
	}

	final private function __clone()
	{
	}

	/***
	 *	get info
	 *
	 *	@param	int			$nMId
	 *	@param	array			$arrFields
	 *	@return null|object		std Class
	 *	@throws RuntimeException
	 */
	public function getTableInfo( $nMId, Array $arrFields = [ [ '*' ] ] )
	{
		if ( ! CMIdLib::isValidMId( $nMId ) )
		{
			return null;
		}

		//	...
		$this->_checkTableParameters();

		//	...
		$objRet	= null;
		$objQ	= null;
		$sField	= DatabaseLib::buildFieldName( $arrFields );
		$sSql	= "SELECT " .
			( CLib::IsExistingString( $sField ) ? addslashes( $sField ) : '*' ) . " ".
			"FROM " . addslashes( $this->m_sTable ) . " " .
			"WHERE " . addslashes( $this->m_sFieldMId ) . " = ?";
		//DB::enableQueryLog();
		$objQ	= DB::selectOne( $sSql,
			[
				intval( $nMId )
			]
		);
		//dd( $sField, $objQ, DB::getQueryLog() );

		if ( CLib::IsObjectWithProperties( $objQ ) )
		{
			$objRet = $objQ;
		}

		return $objRet;
	}

	public function getTableInfoList( $nMId, Array $arrFields = [ [ '*' ] ] )
	{
		if ( ! CMIdLib::isValidMId( $nMId ) )
		{
			return null;
		}

		//	...
		$this->_checkTableParameters();

		//	...
		$arrRet	= null;
		$arrQ	= null;
		$sField	= DatabaseLib::buildFieldName( $arrFields );
		$sSql	= "SELECT " .
			( CLib::IsExistingString( $sField ) ? addslashes( $sField ) : '*' ) . " ".
			"FROM " . addslashes( $this->m_sTable ) . " " .
			"WHERE " . addslashes( $this->m_sFieldMId ) . " = ?";
		//DB::enableQueryLog();
		$arrQ	= DB::select( $sSql,
			[
				intval( $nMId )
			]
		);
		//dd( $sField, $objQ, DB::getQueryLog() );

		if (  CLib::IsArrayWithKeys( $arrQ ) )
		{
			$arrRet = $arrQ;
		}

		return $arrRet;
	}

	/***
	 *	is exists item
	 *
	 *	@param	int	$nMId
	 *	@return bool
	 *	@throws RuntimeException
	 */
	public function isExists( $nMId )
	{
		if ( ! CMIdLib::isValidMId( $nMId ) )
		{
			return false;
		}

		//	...
		$this->_checkTableParameters();

		//	...
		$objQ	= $this->getTableInfo( $nMId, [ [ $this->m_sFieldMId ] ] );
		return CLib::IsObjectWithProperties( $objQ );
	}

	/***
	 *	get list
	 *
	 *	@param array|null	$arrOptions
	 *	@param int|null		$nPage
	 *	@param int|null		$nPageSize
	 *	@return array|null|object
	 *	@throws RuntimeException
	 */
	public function getTableList( Array $arrOptions = null, $nPage = null, $nPageSize = null )
	{
		$arrRet	= [];
		$arrQ	= $this->getTableData( [ '*' ], $arrOptions, $nPage, $nPageSize );
		if ( CLib::IsArrayWithKeys( $arrQ ) )
		{
			$arrRet = $arrQ;
		}

		return $arrRet;
	}

	/***
	 *	get count
	 *
	 *	@param array|null	$arrOptions
	 *	@return int
	 *	@throws RuntimeException
	 */
	public function getTableCount( Array $arrOptions = null )
	{
		$nRet	= 0;
		$objQ	= $this->getTableData
		(
			[ [ 'COUNT(*) AS t_count' ] ],
			$arrOptions
		);
		if ( CLib::IsObjectWithProperties( $objQ, 't_count' ) )
		{
			$nRet = intval( $objQ->t_count );
		}

		return $nRet;
	}

	
	/***
	 *	get data/list
	 *
	 *	@param	array			$arrFields
	 *	@param	array|null		$arrOptions
	 *	@param	int|null		$nPage
	 *	@param	int|null		$nPageSize
	 *	@return	null|array|object	std Class or array
	 *	@throws	RuntimeException
	 */
	public function getTableData( Array $arrFields, Array $arrOptions = null, $nPage = null, $nPageSize = null )
	{
		if ( ! CLib::IsArrayWithKeys( $arrFields ) )
		{
			return null;
		}

		//	...
		$this->_checkTableParameters();

		//	...
		$vRet		= null;
		$sField		= DatabaseLib::buildFieldName( $arrFields );
		$nPage		= ParameterLib::getSafePage( $nPage );
		$nPageSize	= ParameterLib::getSafePageSize( $nPageSize );
		$sSubSql	= '';

		if ( CLib::IsArrayWithKeys( $arrOptions ) )
		{
			foreach ( $arrOptions as $sOptionKey => $sOptionValue )
			{
				if ( is_numeric( $sOptionValue ) )
				{
					$sSubSql .= sprintf
					(
						" AND %s = %d ",
						addslashes( $sOptionKey ),
						addslashes( intval( $sOptionValue ) )
					);
				}
				else if ( is_string( $sOptionValue ) )
				{
					$sSubSql .= sprintf
					(
						" AND %s = '%s' ",
						addslashes( $sOptionKey ),
						addslashes( $sOptionValue )
					);
				}
			}
		}

		//	...
		//DB::enableQueryLog();
		$sSql	= "SELECT " .
			( CLib::IsExistingString( $sField ) ? addslashes( $sField ) : '*' ) . " " .
			"FROM " . addslashes( $this->m_sTable ) . " " .
			"WHERE 1 = 1 " . $sSubSql;
		if ( strstr( $sField, 'COUNT' ) )
		{
			$objQ	= DB::selectOne( $sSql );
			if ( CLib::IsObjectWithProperties( $objQ ) )
			{
				$vRet = $objQ;
			}
		}
		else
		{
			$sSql	.= " " .
				"ORDER BY " . addslashes( $this->m_sFieldId ) . " DESC " .
				"LIMIT ?, ? ";
			$arrQ	= DB::select( $sSql,
				[
					ParameterLib::getSafePageStart( $nPage, $nPageSize ),
					$nPageSize
				]
			);
			if ( CLib::IsArrayWithKeys( $arrQ ) )
			{
				$vRet = $arrQ;
			}
		}
		//dd( $vRet, DB::getQueryLog() );

		return $vRet;
	}
	
	
	/**
	 *	@param	array		$arrFields
	 *	@param	array|null	$arrOptions
	 *	@param	int|null	$nPage
	 *	@param	int|null	$nPageSize
	 *	@return	array|null
	 *	@throws	RuntimeException
	 */
	public function getTableDatas( Array $arrFields, Array $arrOptions = null, $nPage = null, $nPageSize = null )
	{
		if ( ! CLib::IsArrayWithKeys( $arrFields ) )
		{
			return null;
		}

		//	...
		$this->_checkTableParameters();

		//	...
		$vRet		= [];
		$sField		= DatabaseLib::buildFieldName( $arrFields );
		$nPage		= ParameterLib::getSafePage( $nPage );
		$nPageSize	= ParameterLib::getSafePageSize( $nPageSize );
		$sSubSql	= '';

		if ( CLib::IsArrayWithKeys( $arrOptions ) )
		{
			foreach ( $arrOptions as $sOptionKey => $sOptionValue )
			{
				if ( is_numeric( $sOptionValue ) )
				{
					$sSubSql .= sprintf
					(
						" AND %s = %d ",
						addslashes( $sOptionKey ),
						addslashes( intval( $sOptionValue ) )
					);
				}
				else if ( is_string( $sOptionValue ) )
				{
					$sSubSql .= sprintf
					(
						" AND %s = '%s' ",
						addslashes( $sOptionKey ),
						addslashes( $sOptionValue )
					);
				}
			}
		}

		//	...
		//DB::enableQueryLog();
		$sSql	= "SELECT " .
			( CLib::IsExistingString( $sField ) ? addslashes( $sField ) : '*' ) . " " .
			"FROM " . addslashes( $this->m_sTable ) . " " .
			"WHERE 1 = 1 " . $sSubSql;
		if ( strstr( $sField, 'COUNT' ) )
		{
			$objQ	= DB::selectOne( $sSql );
			if ( CLib::IsObjectWithProperties( $objQ ) )
			{
				$vRet = $objQ;
			}
		}
		else
		{
			$sSql	.= " " .
				"ORDER BY " . addslashes( $this->m_sFieldId ) . " ASC " .
				"LIMIT ?, ? ";
			$arrQ	= DB::select( $sSql,
				[
					ParameterLib::getSafePageStart( $nPage, $nPageSize ),
					$nPageSize
				]
			);
			if ( CLib::IsArrayWithKeys( $arrQ ) )
			{
				$vRet = $arrQ;
			}
		}
		//dd( $vRet, DB::getQueryLog() );

		return $vRet;
	}

	public function object_array($object) {
		$array = null;
		if (is_object($object)) {
			foreach ($object as $key => $value) {
				$array[$key] = $value;
			}
		}
		else {
			$array = $object;
		}
		return $array;
	}

	////////////////////////////////////////////////////////////////////////////////
	//	Private
	//



	/***
	 *	check table configuration is corrected
	 *
	 *	@return	bool
	 *	@throws	RuntimeException
	 */
	private function _checkTableParameters()
	{
		if ( CLib::IsExistingString( $this->m_sTable, true ) &&
			CLib::IsExistingString( $this->m_sFieldId, true ) &&
			CLib::IsExistingString( $this->m_sFieldMId, true ) &&
			CLib::IsExistingString( $this->m_sFieldStatus, true ) )
		{
			return true;
		}
		else
		{
			throw new RuntimeException( CConst::ERROR_EXCEPTION );
		}
	}

}