<?php
namespace Tests;

use App\Http\Constants;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;



/**
 *	Class MakeErrorIdsLang
 *	@package Tests
 */
class MakeErrorIdsLang extends BaseTestCase
{
	use CreatesApplication;

	//	...
	var $m_arrClassList =
	[
		Constants\CErrCodeController::class,
		Constants\CErrCodeDispatcher::class,
		Constants\CErrCodeLib::class,
		Constants\CErrCodeMiddleware::class,
		Constants\CErrCodeModelDataHub::class,
		Constants\CErrCodeServices::class,
	];


	public function testMakeErrorIdsLang()
	{
		//	计算循环数
		$i = 0;
		echo PHP_EOL;

		foreach ( $this->m_arrClassList as $oClass )
		{
			$arrPrintInfo	= [];
			$arrInfo	= $this->_getConstantInfo( $oClass );

			echo "[" . $oClass . "]" . PHP_EOL;
			foreach ( $arrInfo as $sConstName => $arrItem )
			{
				printf( "%s\t%d\t%s", $sConstName, $arrItem['value'], $arrItem['comment'], $arrItem['route'] );
				echo PHP_EOL;
				$arrPrintInfo[] =
					[
						'key'		=> $sConstName,
						'value'		=> $arrItem['value'],
						'comment'	=> $arrItem['comment'],
						'route'		=> $arrItem['route']
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
				'route'		=> $cConstParser->getRoute( $sConstName ),
			];
		}

		return $arrRet;
	}
}