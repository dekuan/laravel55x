<?php
/**
 * Created by PhpStorm.
 * User: liuqixing
 * Date: 2018/1/7
 * Time: 上午1:21
 */

require_once ( '/Users/liuqixing/wwwroot/kudian/websites/com.kulaidian.call.api/vendor/aliyuncs/oss-sdk-php/autoload.php' );

use OSS\OssClient;
use OSS\Core\OssException;


$sAccessKeyId		= 'LTAIVXtZHObyZJFO';
$sAccessKeySecret	= 'ytpnauS9SRtURQR2XBLALJK7s6tAXK';
$sBucketName		= 'kudian-kld-object';
$sEndPoint		= 'oss-cn-beijing.aliyuncs.com';
$nTimeOut		= 5;
$nConnectTimeout	= 10;
$sKey			= '111111.jpg';
$sLocalFullFilename	= '/Users/liuqixing/wwwroot/kudian/websites/com.kulaidian.call.api/storage/uploader/pictureJ4agAQ.jpg';


//	...
$oOssClient = new OssClient( $sAccessKeyId, $sAccessKeySecret, $sEndPoint );
$oOssClient->setTimeout( $nTimeOut );
$oOssClient->setConnectTimeout( $nConnectTimeout );

var_dump( 'sBucketName=' . $sBucketName, $sKey, $sLocalFullFilename );

try
{
	$infoRtn = $oOssClient->uploadFile( $sBucketName, $sKey, $sLocalFullFilename );
	print_r( $infoRtn );
}
catch ( OssException $e )
{
	var_dump( $e->getMessage() );
}

