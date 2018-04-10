<?php

namespace Tests\Unit;

use App\Http\Services\SrvMp3File\CSrvMp3File;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;



/**
 *	Class testCSrvMp3File
 *	@package Tests\Unit
 */
class testCSrvMp3File extends TestCase
{
	/**
	*	A basic test example.
	*
	*	@return void
	*/
	public function testExtractMp3Info()
	{

		$sDir		= '/tmp/mp3/';
		$arrMp3Files	= scandir( $sDir );
		if ( is_array( $arrMp3Files ) )
		{
			$nCountTotal	= 0;
			$nCountSuccess	= 0;

			foreach ( $arrMp3Files as $sMp3File )
			{
				$sMp3FFN	= sprintf( "%s%s", $sDir, $sMp3File );
				if ( ! is_file( $sMp3FFN ) )
				{
					continue;
				}

				$nCountTotal	++;

				// Initialize getID3 engine
				$cSrvMp3File	= new CSrvMp3File();
				$objFileInfo	= $cSrvMp3File->extractMp3Info( $sMp3FFN );

				if ( is_object( $objFileInfo ) )
				{
					$nCountSuccess ++;

					echo "\r\n";
					echo "------------------------------------------------------------\r\n";
					echo $sMp3FFN;
					echo "\r\n";
					printf
					(
						"title\t: %s\r\nartist\t: %s\r\nalbum\t: %s\r\n",
						$objFileInfo->title,
						$objFileInfo->artist,
						$objFileInfo->album
					);
				}
			}

			printf( "\r\nnCountTotal=$nCountTotal\r\nnCountSuccess=$nCountSuccess\r\n" );


		}
	}

}
