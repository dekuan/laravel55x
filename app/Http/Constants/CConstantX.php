<?php

namespace App\Http\Constants;
use dekuan\vdata\CConst;


/***
 *	Class CConstantX
 *	@package App\Http\Constants
 */
class CConstantX
{
	//
	//	rights list for filter
	//
	const ROLE_UNKNOWN			= 0;		//	0
	const ROLE_ANYONE			= ( 1 << 0 );	//	1
	const ROLE_MEMBER			= ( 1 << 1 );	//	2
	const ROLE_EDITOR			= ( 1 << 2 );	//	4
	const ROLE_OPERATOR			= ( 1 << 3 );	//	8
	const ROLE_ADMIN			= ( 1 << 4 );	//	16
	const ROLE_SUPER_ADMIN			= ( 1 << 5 );	//	32
	const ROLE_COLLABORATOR			= self::ROLE_EDITOR | self::ROLE_OPERATOR | self::ROLE_ADMIN;


	//
	//	user status
	//
	const USER_STATUS_UNKNOWN		= 0;	//	unknown status
	const USER_STATUS_OKAY			= CConst::STATUS_OKAY;		//	okay now, normally
	const USER_STATUS_PENDING		= CConst::STATUS_PENDING;	//	pending
	const USER_STATUS_DENIED		= CConst::STATUS_DENIED;	//	user was denied by he/she organization


	//
	//	default values
	//
	const DEFAULT_PAGE			= 1;		//	page number
	const DEFAULT_PAGE_SIZE			= 10;		//	page size


	//
	//	task_order_table | order status
	//
	const TSKOD_ORDER_STATUS_UNKNOWN		= 0;	//
	const TSKOD_ORDER_STATUS_TODO			= 1;	//    已派单，等待接单
	const TSKOD_ORDER_STATUS_REJECTED		= 2;	//    未接单 > 拒绝接单
	const TSKOD_ORDER_STATUS_DOING			= 3;	//    已接单 > 制作中
	const TSKOD_ORDER_STATUS_DONE			= 4;	//    制作完成
	
	const TSKOD_ORDER_STATUS_CONFIRM_TIMEOUT	= 100;	//    超时未接单 virtual status
	const TSKOD_ORDER_STATUS_FINISH_TIMEOUT		= 101;	//    超时未完成 virtual status
	const TSKOD_ORDER_STATUS_REVIEW_REJECTED	= 102;	//    审核不通过 virtual status



	
	//
	//	task_order_table | review status
	//
	const TSKOD_REVIEW_STATUS_UNKNOWN	= 0;	//
	const TSKOD_REVIEW_STATUS_UNPROCESSED	= 1;	//	未审核的
	const TSKOD_REVIEW_STATUS_APPROVED	= 2;	//	审核通过的
	const TSKOD_REVIEW_STATUS_REJECTED	= 3;	//	未通过的

        //
	//	vt_count_type
	//
	const V_COUNT_TYPE_PLAY			= 1;		//	count type of play
	const V_COUNT_TYPE_SET			= 2;		//
	const V_COUNT_TYPE_DOWNLOAD		= 3;		//
	
	
	
	
	////////////////////////////////////////////////////////////////////////////////
	//	functions
	//
	static function isValidCountType( $nVal )
	{
		return ( is_numeric( $nVal ) &&
			(
				self::V_COUNT_TYPE_PLAY == $nVal ||
				self::V_COUNT_TYPE_SET == $nVal ||
				self::V_COUNT_TYPE_DOWNLOAD == $nVal
			) );
	}

	static function isValidTskOdOrderStatus( $nVal )
	{
		return ( is_numeric( $nVal ) &&
			(
				self::TSKOD_ORDER_STATUS_TODO == $nVal ||
				self::TSKOD_ORDER_STATUS_REJECTED == $nVal ||
				self::TSKOD_ORDER_STATUS_DOING == $nVal ||
				self::TSKOD_ORDER_STATUS_DONE == $nVal
			) );
	}
	static function isValidTskOdReviewStatus( $nVal )
	{
		return ( is_numeric( $nVal ) &&
			(
				self::TSKOD_REVIEW_STATUS_UNPROCESSED == $nVal ||
				self::TSKOD_REVIEW_STATUS_APPROVED == $nVal ||
				self::TSKOD_REVIEW_STATUS_REJECTED == $nVal
			) );
	}


	static function isValidUserStatus( $nVal )
	{
		return ( is_numeric( $nVal ) &&
			(
				self::USER_STATUS_UNKNOWN == $nVal ||
				self::USER_STATUS_OKAY == $nVal ||
				self::USER_STATUS_DENIED == $nVal
			) );
	}

	static function isValidRole( $nVal )
	{
		return ( is_numeric( $nVal ) &&
			(
				self::ROLE_ANYONE == $nVal ||
				self::ROLE_MEMBER == $nVal ||
				self::ROLE_EDITOR == $nVal ||
				self::ROLE_OPERATOR == $nVal ||
				self::ROLE_ADMIN == $nVal ||
				self::ROLE_SUPER_ADMIN == $nVal ||
				self::ROLE_COLLABORATOR == $nVal
			) );
	}
	static function getRoleName( $nRole )
	{
		$sRet	= '';
		$nRole	= intval( $nRole );
		
		switch ( $nRole )
		{
			case self::ROLE_ANYONE :
				$sRet	= '游客';
				break;
			case self::ROLE_MEMBER :
				$sRet	= '会员';
				break;
			case self::ROLE_EDITOR :
				$sRet	= '剪辑师';
				break;
			case self::ROLE_OPERATOR :
				$sRet	= '运营员';
				break;
			case self::ROLE_ADMIN :
				$sRet	= '管理员';
				break;
			case self::ROLE_SUPER_ADMIN :
				$sRet	= '超级管理员';
				break;
			default:
				$sRet	= '未知';
				break;				
		}

		return $sRet;
	}
	
	
	
}