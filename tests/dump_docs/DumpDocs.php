<?php
namespace Tests;

require_once( __DIR__ . '/PrintMdDocs.php' );

use App\Http\Constants;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;


/**
 * Created by PhpStorm.
 * User: xing
 * Date: 27/12/2016
 * Time: 10:59 AM
 */
class DumpDocs extends BaseTestCase
{
	use CreatesApplication;
    
	const IS_NEW		= true;		//	是追加还是重新生成 false:追加
	const FILE_NAME		= __DIR__ . '/error_code_list.md';


	public function testDumpDocumentation( & $is_new = self::IS_NEW )
	{
		//	计算循环数
		$i = 0;
		echo PHP_EOL;

		$arrClassList =
			[
				Constants\CErrCodeController::class,
				Constants\CErrCodeDispatcher::class,
				Constants\CErrCodeLib::class,
				Constants\CErrCodeMiddleware::class,
				Constants\CErrCodeModelDataHub::class,
				Constants\CErrCodeServices::class,
			];
		$arrPrintTitle =
			[
				'错误码KEY'	=> 'key',
				'错误码值'	=> 'value',
				'错误码描述'	=> 'comment',
				'路由'		=>  'rout'
			];

		$arrPrintInfo = [];

		PrintMdDocs::docPrintheader( $arrClassList, $sFileName = self::FILE_NAME, $is_new );

		foreach ( $arrClassList as $oClass )
		{
			$arrPrintInfo = [];
			$arrInfo = [];

			$arrInfo	= $this->_GetConstantInfo( $oClass );

			echo "[" . $oClass . "]" . PHP_EOL;
			foreach ( $arrInfo as $sConstName => $arrItem )
			{
				printf( "%s\t%d\t%s", $sConstName, $arrItem['value'], $arrItem['comment'],$arrItem['rout'] );
				echo PHP_EOL;
				$arrPrintInfo[] =
					[
						'key'     => $sConstName,
						'value'   => $arrItem['value'],
						'comment' => $arrItem['comment'],
						'rout'    => $arrItem['rout']
					];
			}

			echo PHP_EOL;
			PrintMdDocs::docPrint( $oClass,$arrPrintTitle, $arrPrintInfo, $sFileName =self::FILE_NAME ,$is_new);
			unset($arrPrintInfo);
			unset($arrInfo);
			unset($oClass);
			$i++;
		}

		var_dump($i);
		exit();
	}

	private function _GetConstantInfo( $oClass )
	{
		$arrRet	= [];

		//	...
		$cConstParser	= new CConstantsParser( $oClass );
		$arrConstants	= $cConstParser->GetConstants();

		foreach ( $arrConstants as $sConstName )
		{
			$arrRet[ $sConstName ] =
			[
				'value'		=> $cConstParser->GetValue( $sConstName ),
				'comment'	=> $cConstParser->GetComment( $sConstName ),
                'rout'      => $cConstParser->GetRout($sConstName),
            ];
		}

		return $arrRet;
	}
}



/**
 * Simple DocComment support for class constants.
 */
class CConstantsParser
{
	private $m_arrValues	= [];
	private $m_arrComments	= [];
    private $m_arrRout      = [];

	public function __construct( $sClass )
	{
		$this->_Parse( new \ReflectionClass( $sClass ) );
	}

	private function _Parse( \ReflectionClass $clazz )
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
						$this->m_arrComments[ $sCommentKey ]	= $this->_GetClean( $sCommentValue );
					}
				}
			}
		}

		//截取路由
		foreach ($this->m_arrComments as $routKey => $routValue)
        {
            $exp = explode('@route',$routValue);
            if(!isset($exp[1]))
            {
                $this->m_arrRout[$routKey] = '没有规范的文档输出';
                continue;
            }
            $this->m_arrComments[$routKey] = $exp[0];
            $this->m_arrRout[$routKey] = $exp[1];
        }
	}

	public function GetConstants()
	{
		$arrRet	= [];

		if ( is_array( $this->m_arrComments ) && count( $this->m_arrComments ) > 0 )
		{
			$arrRet = array_keys( $this->m_arrComments );
		}

		return $arrRet;
	}

	public function GetComments()
	{
		return $this->m_arrComments;
	}

	public function GetValue( $sName )
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

	public function GetComment( $sName )
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


    public function GetRout($sName)
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


	private function _GetClean( $sStr )
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