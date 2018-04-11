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
				'路由'		=>  'route'
			];

		$arrPrintInfo = [];

		PrintMdDocs::docPrintHeader( $arrClassList, $sFileName = self::FILE_NAME, $is_new );

		foreach ( $arrClassList as $oClass )
		{
			$arrPrintInfo = [];
			$arrInfo = [];

			$arrInfo	= $this->_getConstantInfo( $oClass );

			echo "[" . $oClass . "]" . PHP_EOL;
			foreach ( $arrInfo as $sConstName => $arrItem )
			{
				printf( "%s\t%d\t%s", $sConstName, $arrItem['value'], $arrItem['comment'], $arrItem['route'] );
				echo PHP_EOL;
				$arrPrintInfo[] =
					[
						'key'		=> $sConstName,
						'value'		=> $arrItem[ 'value' ],
						'comment'	=> $arrItem[ 'comment' ],
						'route'		=> $arrItem[ 'route' ]
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

	private function _getConstantInfo( $oClass )
	{
		$arrRet	= [];

		//	...
		$cConstParser	= new CConstantsParser( $oClass );
		$arrConstants	= $cConstParser->getConstants();

		foreach ( $arrConstants as $sConstName )
		{
			$arrRet[ $sConstName ] =
			[
				'value'		=> $cConstParser->getValue( $sConstName ),
				'comment'	=> $cConstParser->getComment( $sConstName ),
				'route'		=> $cConstParser->getRoute( $sConstName ),
			];
		}

		return $arrRet;
	}
}


