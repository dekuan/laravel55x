<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});



/**
 *
 */
Route::group( [ 'middleware' => 'api' ], function()
{
	/***
	 *	For Everyone
	 */
	Route::group( [ 'middleware' => [ 'ApiCheckRoleAnyone' ] ], function()
	{
		Route::get('/api/upload',function (){
			return view('task');
		});

		//	list all task with tsk_status = OKAY
		Route::get(
			'/api/everyone/task/list',
			'ApiTaskController@getApiEveryoneTaskList' );
		Route::get(
			'/api/everyone/task/info',
			'ApiTaskController@getApiEveryoneTaskInfo' );

		//	list all task order list
		Route::get(
			'/api/everyone/task/order/list',
			'ApiTaskOrderController@getApiEveryoneTaskOrderList' );
//		Route::post(
//			'/api/member/upload/audio',
//			'ApiUploaderController@postApiMemberUploadAudio' );
		Route::get(
			'/api/everyone/search/task',
			'ApiTaskController@getApiEveryoneSearchTask' );
		Route::get(
			'/api/everyone/video/list',
			'ApiUploaderController@getApiEveryoneVideoList' );
		Route::get(
			'/api/everyone/video/info',
			'ApiUploaderController@getApiEveryoneVideoInfo' );
		Route::get(
			'/api/everyone/tsk_mid/video/list',
			'ApiVideoController@getApiEveryoneTskMIdVideoList' );

	});


	/**
	 *	For Normal Members( already logged in )
	 */
	Route::group( [ 'middleware' => [ 'ApiCheckRoleMember' ] ], function()
	{
		//	main page
		Route::get(
			'/api/member/main',
			'ApiUserController@getApiMemberMain' );

		//	get user's preference
		//	- my default video
		//	- video calling by my friends
		Route::get(
			'/api/member/preference',
			'ApiUserController@getApiMemberPreference' );
		
		//	set default video
		Route::patch(
			'/api/member/preference/default/video',
			'ApiUserController@patchApiMemberPreferenceDefaultVideo' );

		//	set video calling by my friends
		Route::patch(
			'/api/member/preference/friend/video',
			'ApiUserController@patchApiMemberPreferenceFriendVideo' );

		//	apply for joining in roles
		Route::get(
			'/api/member/join/apply/{pref_role}',
			'ApiUserController@getApiMemberJoinApply' );
		Route::post(
			'/api/member/join/apply/{pref_role}',
			'ApiUserController@postApiMemberJoinApply' );

		//	submit task
		Route::get(
			'/api/member/task/save',
			'ApiTaskController@getApiMemberTaskSave' );
		Route::post(
			'/api/member/task/save',
			'ApiTaskController@postApiMemberTaskSave' );

		//	list all tasks owned by current logged in user 
		Route::get(
			'/api/member/task/owner/list',
			'ApiTaskController@getApiMemberTaskOwnerList' );

		//	list all liked tasks by current logged in user 
		Route::get(
			'/api/member/task/liked/list',
			'ApiTaskController@getApiMemberTaskLikedList' );


		//	my task info
		Route::get(
			'/api/member/task/info',
			'ApiTaskController@getApiMemberTaskInfo' );

		//
		//	TODO
		//		seed csrf_token, post_sign
		//
		//	like a task
		//
		Route::post(
			'/api/member/task/like',
			'ApiTaskController@postApiMemberTaskLike' );


		//	upload picture
		Route::post(
			'/api/member/upload/picture',
			'ApiUploaderController@postApiMemberUploadPicture' );

		//	upload audio and its clips
		Route::post(
			'/api/member/upload/audio',
			'ApiUploaderController@postApiMemberUploadAudio' );


		//	contacts
		Route::post(
			'/api/member/contacts/sync',
			'ApiContactsController@postApiMemberContactsSync' );

		Route::patch(
			'/api/video/set',
			'ApiUploaderController@setCountSet' );

	});


	/**
	 *	For Administrators
	 */
	Route::group( [ 'middleware' => [ 'ApiCheckRoleAdmin' ] ], function()
	{
		//	invite a user for joining
		Route::get(
			'/api/admin/join/welcome/{pref_role}',
			'ApiUserController@getApiAdminJoinWelcome' );

		//	list all type of users
		Route::get(
			'/api/admin/user/list/{pref_role}',
			'ApiUserController@getApiAdminUserList' );

		//	modify user's profile
		Route::get(
			'/api/admin/user/info',
			'ApiUserController@getApiAdminUserInfo' );
		Route::post(
			'/api/admin/user/save',
			'ApiUserController@postApiAdminUserSave' );
	});


	/**
	 *	For Operators
	 */
	Route::group( [ 'middleware' => [ 'ApiCheckRoleOperator' ] ], function()
	{
		//	get task list
		//	see:
		//		group ApiCheckRoleMember, /api/task/list
		//		

		
		//	get available editor list
		Route::get(
			'/api/operator/editor/list',
			'ApiUserController@getApiOperatorEditorList' );

		//	get task list
		Route::get(
			'/api/operator/task/list',
			'ApiTaskController@getApiOperatorTaskList' );

		//	get task info
		Route::get(
			'/api/operator/task/info',
			'ApiTaskController@getApiOperatorTaskInfo' );

		//	create a new or more order and dispatch them to editors
		Route::get(
			'/api/operator/task/order/dispatch',
			'ApiTaskOrderController@getApiOperatorTaskOrderDispatch' );
		Route::post(
			'/api/operator/task/order/dispatch',
			'ApiTaskOrderController@postApiOperatorTaskOrderDispatch' );

		//	get task order list
		Route::get(
			'/api/operator/task/order/list',
			'ApiTaskOrderController@getApiOperatorTaskOrderList' );

		//	view task order info
		Route::get(
			'/api/operator/task/order/info',
			'ApiTaskOrderController@getApiOperatorTaskOrderInfo' );

		//	review task order
		Route::patch(
			'/api/operator/task/order/review',
			'ApiTaskOrderController@patchApiOperatorTaskOrderReview' );

		//	get video list
		Route::get(
			'/api/operator/video/list',
			'ApiVideoController@getApiOperatorVideoList' );

		//	get video info
		Route::get(
			'/api/operator/video/info',
			'ApiVideoController@getApiOperatorVideoInfo' );


		Route::get(
			'/api/operator/task/order/problem/list',
			'ApiTaskOrderController@getApiOperatorTaskOrderProblemList' );

		
		Route::post(
			'/api/operator/upload/cover/by/video',
			'ApiUploaderController@postApiOperatorUploadCoverByVideo'
		);

	});


	/**
	 *	For Editors
	 */
	Route::group( [ 'middleware' => [ 'ApiCheckRoleEditor' ] ], function()
	{
		//	get task order list
		Route::get(
			'/api/editor/task/order/list',
			'ApiTaskOrderController@getApiEditorTaskOrderList' );

		//	view task order info
		Route::get(
			'/api/editor/task/order/info',
			'ApiTaskOrderController@getApiEditorTaskOrderInfo' );

		//	accept or reject a task order
		Route::patch(
			'/api/editor/task/order/confirm',
			'ApiTaskOrderController@getApiEditorTaskOrderConfirm' );

		//	finish a task order
		Route::patch(
			'/api/editor/task/order/finish',
			'ApiTaskOrderController@patchApiEditorTaskOrderFinish' );

//		Route::get(
//			'/api/editor/task/order/problem/list',
//			'ApiTaskOrderController@getApiEditorTaskOrderProblemList' );

		//
		//	uploader
		//
		Route::post(
			'/api/editor/upload/video',
			'ApiUploaderController@postApiEditorUploadVideo'
		);
		Route::post(
			'/api/editor/upload/cover/by/video',
			'ApiUploaderController@postApiEditorUploadCoverByVideo'
		);
		Route::post(
			'/api/editor/upload/cover',
			'ApiUploaderController@postApiEditorUploadCover'
		);
		Route::post(
			'/api/editor/upload/audio',
			'ApiUploaderController@postApiEditorUploadAudio'
		);

		Route::post(
			'/api/editor/save/info',
			'ApiUploaderController@postApiEditorSaveInfo');

	});
	

});