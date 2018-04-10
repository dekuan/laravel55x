<?php

namespace Tests\Unit;

use dekuan\delib\CLib;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Http\Models\DataHub;



class testCDataHubUser extends TestCase
{
	/**
	*	A basic test example.
	*
	*	@return void
	*/
	public function testGetUMIdByULoginMobileWithCache()
	{
		$arrData =
		[
			'18811070903',
			'13810628083'
		];

		$cUser	= new DataHub\CDataHubUser();
		
		echo "\n";
		
		foreach ( $arrData as $sMob )
		{
			$nUMId	= $cUser->getUMIdByULoginMobileWithCache( $sMob );
			
			printf( "$sMob\t-> $nUMId\n" );
		}

		$this->assertTrue(true);
	}
	
	public function testSyncContacts()
	{
		$arrData =
			[
				'+86018811070903',
				'+86018811070903',
				'+86018811070903',
				'+86018811070903',
				'013810628083'
			];

		$cContacts	= new DataHub\CDataHubContacts();
		
		echo "\n";
		$arrResult	= $cContacts->syncContacts( $arrData );
		if ( CLib::IsArrayWithKeys( $arrResult ) )
		{
			print_r( $arrResult );
		}
		else
		{
			var_dump( $arrResult );
		}
	}

}
