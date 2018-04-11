<?php
namespace Tests;

use App\Http\Constants;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;



/**
 *	Class DumpDocs
 *	@package Tests
 */
class MakeErrorIdsLang extends BaseTestCase
{
	use CreatesApplication;

	const IS_NEW		= true;		//	是追加还是重新生成 false:追加
	const FILE_NAME		= __DIR__ . '/error_code_list.md';


	public function testMakeErrorIdsLang()
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

		foreach ( $arrClassList as $oClass )
		{
			$arrPrintInfo = [];
			$arrInfo = [];

			$arrInfo	= $this->_getConstantInfo( $oClass );

			echo "[" . $oClass . "]" . PHP_EOL;
			foreach ( $arrInfo as $sConstName => $arrItem )
			{
				printf( "%s\t%d\t%s", $sConstName, $arrItem['value'], $arrItem['comment'], $arrItem['rout'] );
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
			unset( $arrPrintInfo );
			unset( $arrInfo );
			unset( $oClass );
			$i ++;
		}
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
				'rout'		=> $cConstParser->getRout( $sConstName ),
			];
		}

		return $arrRet;
	}
}