<?php


/**
 *	hosts and domains
 *	passport
 */
if ( ! defined( '__CONFIG_XC__' ) )
{
	define( '__CONFIG_XC__', true );

	//	...
	define( 'CONFIG_XC_HOST_CALL_API',	'api.call.30mai.cn' );
	define( 'CONFIG_XC_HOST_CALL_UI',	'ui.call.30mai.cn' );
	define( 'CONFIG_XC_HOST_COOKIE',	'.30mai.cn' );
	define( 'CONFIG_XC_HOST_MAIN',		'30mai.cn' );
	define( 'CONFIG_XC_HOST_ACCOUNT',	'account.30mai.cn' );
	define( 'CONFIG_XC_HOST_OBJECT',	'kudian-kld-object.oss-cn-beijing.aliyuncs.com' );
	define( 'CONFIG_XC_HOST_AVATAR',	'kudian-kld-avatar.oss-cn-beijing.aliyuncs.com' );
	define( 'CONFIG_XC_HOST_CLIP',		'kudian-kld-clip.oss-cn-beijing.aliyuncs.com' );
	define( 'CONFIG_XC_HOST_VIDEO',	        'kudian-kld-video.oss-cn-beijing.aliyuncs.com' );
}


return [

	'host'	=>
	[
		'root'		=> CONFIG_XC_HOST_COOKIE,
		'cookie'	=> CONFIG_XC_HOST_COOKIE,
		'main'		=> CONFIG_XC_HOST_MAIN,
		'call_api'	=> CONFIG_XC_HOST_CALL_API,
		'call_ui'	=> CONFIG_XC_HOST_CALL_UI,
		'account'	=> CONFIG_XC_HOST_ACCOUNT,		
		'object'	=> CONFIG_XC_HOST_OBJECT,
		'avatar'	=> CONFIG_XC_HOST_AVATAR,
		'clip'		=> CONFIG_XC_HOST_CLIP,
		'video'		=> CONFIG_XC_HOST_VIDEO,
	],

	
	'cookie'	=>
	[
	],


	//
	//	services      oss-cn-beijing-internal.aliyuncs.com
	//
	'services'	=>
	[
		'srvpicturestorage'	=>
		[
			'oss'	=>
			[
				'access_key_id'			=> 'LTAIVXtZHObyZJFO',
				'access_key_secret'		=> 'ytpnauS9SRtURQR2XBLALJK7s6tAXK',
				'bucket_name'			=> 'kudian-kld-object',
				'bucket_url'			=> 'oss-cn-beijing-internal.aliyuncs.com',	//	'oss-cn-beijing.aliyuncs.com',
				'http_timeout'			=> 20,
				'tcp_connect_timeout'		=> 30,
				'file_field'			=> 'defile',
				'max_upload_file_size'		=> 10 * 1024 * 1024,
				'allowed_mime_type_list'	=> [ 'image/jpeg', 'image/png' ],
			]
		],
		'srvclipstorage'	=>
		[
			'oss'	=>
			[
				'access_key_id'			=> 'LTAIO9mMeBMuVmS0',
				'access_key_secret'		=> 'gTfLtlFadMxwYDzFNE8AHNN0I0fqmm',
				'bucket_name'			=> 'kudian-kld-clip',
				'bucket_url'			=> 'oss-cn-beijing-internal.aliyuncs.com',	//	'oss-cn-beijing.aliyuncs.com',
				'http_timeout'			=> 20,
				'tcp_connect_timeout'		=> 30,
				'file_field'			=> 'defile',
				'max_upload_file_size'		=> 20 * 1024 * 1024,
				'allowed_mime_type_list'	=> [ 'audio/mpeg', 'application/octet-stream', 'audio/x-wav', 'audio/x-m4a'],
			]
		],
		'srvvideostorage'	=>
		[
			'oss'	=>
			[
				'access_key_id'			=> 'LTAIvbLcDY8eY8k6',
				'access_key_secret'		=> 'dJTSgmTtGP6RXy6XujCsry0rf4lAb1',
				'bucket_name'			=> 'kudian-kld-video',
				'bucket_url'			=> 'oss-cn-beijing-internal.aliyuncs.com',	//	'oss-cn-beijing.aliyuncs.com',
				'http_timeout'			=> 20,
				'tcp_connect_timeout'		=> 30,
				'file_field'			=> 'defile',
				'max_upload_file_size'		=> 20 * 1024 * 1024,
				'allowed_mime_type_list'	=> [ 'image/jpeg', 'image/png', 'video/mp4', 'video/x-m4v', 'audio/mpeg', 'audio/x-wav', 'audio/x-m4a', 'application/octet-stream' ],
			]
		]
	],


	'sign'	=>
	[
		'common'	=> '11ac7bae7165c3817dd40bac4bb49b6e3592355b090871f73c2d765d8c33f5f6f0269774578758201b34ccec9216112ea934120b6f29efcebf505d1df9c4b160',
	],





	//
	//	core
	//
	'core'	=>
	[
		'cookie_seed' 	=> '515FB97D-F071-44C7-9BAC-4FC2B2943898--1E0961FD-DB01-4529-89D9-AE55C152D1BE',
	],



	//
	//	configuration of writing log
	//
	'record_file_log'	=> true,	//	纪录日志
	'record_sql_log'	=> true,	//	纪录日志
];