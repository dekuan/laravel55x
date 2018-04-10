<?php

namespace App\Http\Constants;


class CErrCodeServices extends CErrCode
{
	//
	//	for picture Storage
	//
	const SRVOBJECTSTORAGE_PICTURE_START			= self::ERROR_SERVICE_START + 0;
	//
	//	SRVOBJECTSTORAGE_PICTURE_START + error code in deobjectstorage
	//	...
	//	...
	//
	const SRVOBJECTSTORAGE_PICTURE_END			= self::ERROR_SERVICE_START + 2999;



	//
	//	for music clip Storage
	//
	const SRVOBJECTSTORAGE_CLIP_START			= self::ERROR_SERVICE_START + 3000;
	//
	//	SRVOBJECTSTORAGE_CLIP_START + error code in deobjectstorage
	//	...
	//	...
	//
	const SRVOBJECTSTORAGE_CLIP_END				= self::ERROR_SERVICE_START + 5999;

	
	//
	//	for others start here
	//
	const ERRCODESERVICES_START				= self::ERROR_SERVICE_START + 20000;


	//
	//	Http/Services/SrvVideoStorage
	//	...
	const SRVOBJECTSTORAGE_VIDEO_START	 		= self::ERROR_SERVICE_START + 6000;	//	...
	const SRVOBJECTSTORAGE_VIDEO_END			= self::ERROR_SERVICE_START + 8999;	//	...


	//
	//   Http/Services/SrvCoverStorage
	//
	const SRVCOVERSTORAGE_AUTO_COVER_START			= self::ERROR_SERVICE_START + 9000;	//	...
	const SRVCOVERSTORAGE_AUTO_COVER_END			= self::ERROR_SERVICE_START + 11999;	//	...

	const SRVCOVERSTORAGE_COVER_START			= self::ERROR_SERVICE_START + 12000;	//	...
	const SRVCOVERSTORAGE_COVER_END				= self::ERROR_SERVICE_START + 14999;	//	...

	//
	//   Http/Services/SrvVoiceStorage
	//
	const SRVCOVERSTORAGE_AUDIO_START			= self::ERROR_SERVICE_START + 15000;	//	...
	const SRVCOVERSTORAGE_AUDIO_END				= self::ERROR_SERVICE_START + 17999;	//	...


}