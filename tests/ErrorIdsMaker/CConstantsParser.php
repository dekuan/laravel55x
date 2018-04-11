<?php
namespace Tests;


/**
 *	Simple DocComment support for class constants.
 */
class CConstantsParser
{
	private $m_arrValues	= [];
	private $m_arrComments	= [];
	private $m_arrRout      = [];


	public function __construct( $sClass )
	{
		$this->_parse( new \ReflectionClass( $sClass ) );
	}



	public function getConstants()
	{
		$arrRet	= [];

		if ( is_array( $this->m_arrComments ) && count( $this->m_arrComments ) > 0 )
		{
			$arrRet = array_keys( $this->m_arrComments );
		}

		return $arrRet;
	}

	public function getComments()
	{
		return $this->m_arrComments;
	}

	public function getValue( $sName )
	{
		if ( ! is_string( $sName ) || 0 == strlen( $sName ) )
		{
			return null;
		}
		if ( ! isset( $this->m_arrValues ) )
		{
			return null;
		}

		//	...
		$vRet	= null;

		if ( array_key_exists( $sName, $this->m_arrValues ) )
		{
			$vRet = $this->m_arrValues[ $sName ];
		}

		return $vRet;
	}

	public function getComment( $sName )
	{
		if ( ! is_string( $sName ) || 0 == strlen( $sName ) )
		{
			return '';
		}
		if ( ! isset( $this->m_arrComments ) )
		{
			return '';
		}

		//	...
		$sRet	= '';

		if ( array_key_exists( $sName, $this->m_arrComments ) )
		{
			$sRet = $this->m_arrComments[ $sName ];
		}
		return $sRet;
	}


	public function getRout($sName)
	{
		if ( ! is_string( $sName ) || 0 == strlen( $sName ) )
		{
			return '';
		}
		if ( ! isset( $this->m_arrComments ) )
		{
			return '';
		}

		//	...
		$sRet	= '';

		if ( array_key_exists( $sName, $this->m_arrRout ) )
		{
			$sRet = $this->m_arrRout[ $sName ];
		}

		return $sRet;
	}



	private function _parse( \ReflectionClass $clazz )
	{
		$content	= file_get_contents( $clazz->getFileName() );
		$arrTokens	= token_get_all( $content );

		//	...
		$arrContains	= [];
		foreach( $arrTokens as $arrItem )
		{
			if ( is_array( $arrItem ) || 3 == count( $arrItem ) )
			{
				$nLine 	= $arrItem[ 2 ];
				if ( is_numeric( $nLine ) )
				{
					if ( ! array_key_exists( $nLine, $arrContains ) )
					{
						$arrContains[ $nLine ] = [];
					}

					$arrContains[ $nLine ][]	= $arrItem;
				}
			}
		}

		foreach ( $arrContains as $nLine => $arrList )
		{
			//	$m_arrComments
			if ( is_array( $arrList ) && count( $arrList ) > 0 )
			{
				$bIsConst	= false;
				$sCommentKey	= '';
				$sCommentValue	= '';

				foreach ( $arrList as $arrItem )
				{
					if ( is_array( $arrItem ) || 3 == count( $arrItem ) )
					{
						$nType 	= $arrItem[ 0 ];
						if ( T_CONST == $nType )
						{
							$bIsConst = true;
							break;
						}
					}
				}
				if ( $bIsConst )
				{
					foreach ( $arrList as $arrItem )
					{
						if ( is_array( $arrItem ) || 3 == count( $arrItem ) )
						{
							$nType 	= $arrItem[ 0 ];
							$sValue = $arrItem[ 1 ];
							//$nLine 	= $arrItem[ 2 ];

							if ( T_COMMENT == $nType && empty( $sCommentValue ) )
							{
								$sCommentValue	= $sValue;
							}
							if ( T_STRING == $nType && empty( $sCommentKey ) )
							{
								$sCommentKey	= $sValue;
							}
						}
					}

					if ( is_string( $sCommentKey ) && strlen( $sCommentKey ) > 0 )
					{
						$this->m_arrValues[ $sCommentKey ]	= $clazz->getConstant( $sCommentKey );
						$this->m_arrComments[ $sCommentKey ]	= $this->_getClean( $sCommentValue );
					}
				}
			}
		}

		//截取路由
		foreach ($this->m_arrComments as $routKey => $routValue)
		{
			$exp = explode('@route',$routValue);
			if ( ! isset( $exp[ 1 ] ) )
			{
				$this->m_arrRout[ $routKey ] = '没有规范的文档输出';
				continue;
			}

			$this->m_arrComments[ $routKey ]	= $exp[ 0 ];
			$this->m_arrRout[ $routKey ]		= $exp[ 1 ];
		}
	}
	
	private function _getClean( $sStr )
	{
		if ( null === $sStr )
		{
			return null;
		}

		//	...
		$sRet		= null;
		$arrLines	= preg_split( '/\R/', $sStr );
		foreach( $arrLines as $sLine )
		{
			$sLine	= trim( $sLine, "/* \t\x0B\0" );
			if ( '' == $sLine )
			{
				continue;
			}

			if ( null != $sRet )
			{
				$sRet .= ' ';
			}

			$sRet .= $sLine;
		}

		//	...
		return $sRet;
	}


}