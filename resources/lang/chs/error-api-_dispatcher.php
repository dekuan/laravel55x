<?php

use App\Http\Constants\CErrCodeDispatcher;


/**
 *	Created by PhpStorm.
 *	User: xing
 *	Date: 01:48 PM October 30, 2017
 */
return [

	//
	//	app/Http/Dispatchers/Api/ApiUploaderDispatcher/ApiUploaderDispatcher_V1_0.php
	//
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_FAILED_UPLOAD_PICTURE						=> '上传图片失败',
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_ERROR_UPLOAD_PICTURE_GET_TEMP_FFN				=> '上传图片时没有获取到临时文件',

	CErrCodeDispatcher::APIUPLOADERDISPATCHER_ERROR_UPLOAD_CLIP						=> '上传音频失败',
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_ERROR_UPLOAD_CLIP_GET_TEMP_FFN				=> '上传音频时没有获取到临时文件',

	CErrCodeDispatcher::APIUPLOADERDISPATCHER_FAILED_UPLOAD_VIDEO						=> '上传视频失败',
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_ERROR_UPLOAD_VIDEO_GET_TEMP_FFN				=> '上传视频时没有获取到临时文件',
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_ERROR_UPLOAD_VIDEO_COVER					=> '上传视频时没有自动截屏失败',
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_FAILED_UPLOAD_COVER_BY_VIDEO					=> '上传视频的封面失败',
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_FAILED_UPLOAD_COVER_BY_VIDEO_PARAM_SNAPSHOT_TIME		=> '上传视频封面参数时间错误',
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_FAILED_UPLOAD_COVER_BY_VIDEO_PARAM_VIDEO_URL			=> '上传视频封面参数视频地址错误',

	CErrCodeDispatcher::APIUPLOADERDISPATCHER_FAILED_UPLOAD_COVER						=> '上传封面失败',
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_ERROR_UPLOAD_COVER_GET_TEMP_FFN				=> '上传封面时没有获取到临时文件',

	CErrCodeDispatcher::APIUPLOADERDISPATCHER_FAILED_SAVE_VIDEO						=> '保存视频失败',
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_ERROR_GETVIDEOLIST_FAILED					=> '获取视频列表失败',
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_ERROR_GETVIDEOLIST_NOT_COUNT					=> '视频列表没有数据',
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_ERROR_SETCOUNTSET_FAILED					=> '视频设置失败',
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_ERROR_UPDATEVIDEO_PARAM_VT_MID				=> '修改视频参数vt_mid错误',
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_ERROR_UPDATEVIDEO_PARAM_V_COUNT_TYPE				=> '修改视频参数v_count_type错误',
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_ERROR_UPDATEVIDEO_FAILED					=> '修改视频失败',
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_ERROR_GETVIDEOINFO_PARAM_VT_MID				=> '获取视频详情参数vt_mid错误',
	CErrCodeDispatcher::APIUPLOADERDISPATCHER_ERROR_GETVIDEOINFO_FAILED					=> '获取视频详情失败',

	//
	//	app/Http/Dispatchers/Api/ApiUserDispatcher/ApiUserDispatcher_V1_0.php
	//
	CErrCodeDispatcher::APIUSERDISPATCHER_ERROR_PATCHAPIMEMBERPREFERENCEDEFAULTVIDEO_FAILED			=> '用户设置默认来电视频失败',
	CErrCodeDispatcher::APIUSERDISPATCHER_ERROR_MEMBERPREFERENCEFRIENDVIDEO_FAILED				=> '用户给好友设置来电视频失败',
	CErrCodeDispatcher::APIUSERDISPATCHER_ERROR_GETAPIOPERATOREDITORLIST_FAILED				=> '运营人员获取剪辑师失败',
	//
	//	app/Http/Dispatchers/Api/ApiTaskDispatcher/ApiTaskDispatcher_V1_0.php
	//
	CErrCodeDispatcher::APITASKDISPATCHER_ERROR_POSTAPIMEMBERTASKSAVE_FAILED				=> '求电发布失败',
	CErrCodeDispatcher::APITASKDISPATCHER_ERROR_POSTAPIMEMBERTASKLIKE_FAILED				=> '打call失败',
	CErrCodeDispatcher::APITASKDISPATCHER_ERROR_GETAPIOPERATORTASKINFO_PARAM_TSK_MID			=> '运营人员任务详情获取参数tsk_mid失败',
	CErrCodeDispatcher::APITASKDISPATCHER_ERROR_GETAPIOPERATORTASKINFO_FAILED				=> '运营人员获取任务详情失败',
	CErrCodeDispatcher::APITASKDISPATCHER_ERROR_GETAPIEVERYONETASKINFO_PARAM_TSK_MID			=> '所有人获取任务详情参数tsk_mid失败',
	CErrCodeDispatcher::APITASKDISPATCHER_ERROR_GETAPIEVERYONETASKINFO_FAILED				=> '所有人获取任务详情失败',

	//
	//	app/Http/Dispatchers/Api/ApiTaskOrderDispatcher/ApiTaskOrderDispatcher_V1_0.php
	//
	CErrCodeDispatcher::APITASKORDERDISPATCHER_ERROR_POSTAPIOPERATORTASKORDERDISPATCH_FAILED		=> '运营人员派单失败',
	CErrCodeDispatcher::APITASKORDERDISPATCHER_ERROR_PATCHAPIOPERATORTASKORDERREVIEW_FAILED			=> '运营人员审核失败',
	CErrCodeDispatcher::APITASKORDERDISPATCHER_ERROR_GETAPIEDITORTASKORDERCONFIRM_FAILED			=> '剪辑师人接单|拒单失败',
	CErrCodeDispatcher::APITASKORDERDISPATCHER_ERROR_PATCHAPIEDITORTASKORDERFINISH_FAILED			=> '剪辑师完成失败',

	//
	//	Http/Dispatchers/Api/ApiContactsDispatcher/ApiContactsDispatcher_V1_0.php
	//
	CErrCodeDispatcher::APICONTACTSDISPATCHER_ERROR_POSTAPIMEMBERCONTACTSSYNC_PARAM_PN			=> '保存用户通讯录参数朋友名字错误',
	CErrCodeDispatcher::APICONTACTSDISPATCHER_ERROR_POSTAPIMEMBERCONTACTSSYNC_FAILED			=> '保存用户通讯录失败',
	CErrCodeDispatcher::APICONTACTSDISPATCHER_ERROR_POSTAPIMEMBERCONTACTSSYNC_SYNCCONTACTS			=> '保存用户通讯录同步失败',

	//
	//	Http/Dispatchers/Rd/RdVideoDispatcher_V1_0.php
	//
	CErrCodeDispatcher::RDVIDEODISPATCHER_ERROR_REDIRECTDOWNLOAD_PARAM_MID					=> '下载参数错误',
	CErrCodeDispatcher::RDVIDEODISPATCHER_ERROR_REDIRECTDOWNLOAD_FAILED					=> '下载失败',
	CErrCodeDispatcher::RDVIDEODISPATCHER_ERROR_REDIRECTDOWNLOAD_FILE_NOT_EXISTS				=> '所下载的文件不存在',

	
	
];