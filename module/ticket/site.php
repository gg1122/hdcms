<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\ticket;

use module\hdSite;
use system\model\Ticket;
use system\model\TicketGroups;
use system\model\TicketModule;
use system\model\TicketRecord;

/**
 * 卡券管理
 * Class site
 * @package module\ticket
 */
class site extends hdSite {
	//	protected $names = [ 1 => '折扣券', 2 => '代金券' ];

	public function __construct() {
		parent::__construct();
		//		$this->db = new Ticket();
		View::with( 'ticket_name', service( 'ticket' )->getTitleByType( q( 'get.type' ) ) );
	}

	//折扣券列表
	public function doSitelists() {
		$sql = "SELECT t.*, count(r.id) as usertotal FROM " . tablename( 'ticket' ) . " t ";
		$sql .= "LEFT JOIN " . tablename( 'ticket_record' ) . " r ";
		$sql .= "ON t.tid=r.tid WHERE t.type=" . q( 'get.type' ) . " GROUP BY t.tid";
		$data = Db::query( $sql );
		View::with( 'data', $data );

		return view( $this->template . '/lists.html' );
	}

	//添加折扣券
	public function doSitepost() {
		$tid    = Request::get( 'tid', 0, 'intval' );
		$ticket = new Ticket();
		if ( IS_POST ) {
			//表单验证
			switch ( Request::get( 'type' ) ) {
				case 1:
					//折扣券
					Validate::make( [
						[ 'condition', 'regexp:/^[0-9\.]+$/', '"满多少钱可使用"只能为数字' ],
						[ 'discount', 'regexp:/^0\.\d$/', '"折扣"只能为0-1的小数' ],
					] );
					break;
				case 2:
					Validate::make( [
						[ 'condition', 'regexp:/^[0-9\.]+$/', '"使用条件"只能为数字' ],
						[ 'discount', 'regexp:/^[0-9\.]+$/', '"代金券面额"只能为数字' ],
					] );
					if ( $_POST['condition'] < $_POST['discount'] ) {
						message( '代金券面额必须少于使用条件的金额。', 'back', 'error' );
					}
					//代金券
					break;
			}

			$ticket['tid'] = $tid;
			foreach ( Request::post() as $k => $v ) {
				$ticket[ $k ] = $v;
			}
			$insertTid = $ticket->save();
			$tid       = $tid ?: $insertTid;
			//模块设置
			$ticketModule = new TicketModule();
			$ticketModule->where( 'tid', $tid )->delete();
			foreach ( Request::post( 'module', [ ] ) as $module ) {
				$ticketModule['tid']    = $tid;
				$ticketModule['module'] = $module;
				$ticketModule->save();
			}
			//会员组
			$ticketGroups = new TicketGroups();
			$ticketGroups->where( 'siteid', SITEID )->where( 'tid', $tid )->delete();
			foreach ( Request::post( 'groups', [ ] ) as $group_id ) {
				$ticketGroups['tid']      = $tid;
				$ticketGroups['group_id'] = $group_id;
				$ticketGroups->save();
			}
			message( '卡券数据更新成功', site_url( 'lists', [ 'type' => q( 'type' ) ] ) );
		}
		//文字描述
		if ( q( 'get.type' ) == 1 ) {
			$msg = [
				'module'      => [ 'title' => '折扣券' ],
				'title'       => [ 'title' => '折扣券名称', 'help' => '' ],
				'condition'   => [ 'title' => '满多少钱可打折', 'help' => '请填写整数。默认订单金额大于0元就可以使用' ],
				'discount'    => [ 'title' => '折扣', 'help' => '请填写0-1的小数。' ],
				'description' => [ 'title' => '折扣券说明', 'help' => '' ],
				'amount'      => [ 'title' => '折扣券总数量', 'help' => '此设置项设置折扣券的总发行数量。' ],
				'limit'       => [ 'title' => '每人可使用数量', 'help' => '此设置项设置每个用户可领取此折扣券数量。' ],
			];
		} else {
			$msg = [
				'module'      => [ 'title' => '代金券' ],
				'title'       => [ 'title' => '代金券名称', 'help' => '' ],
				'condition'   => [ 'title' => '使用条件', 'help' => '订单满多少钱可用。' ],
				'discount'    => [ 'title' => '代金券面额', 'help' => '代金券面额必须少于使用条件的金额。' ],
				'description' => [ 'title' => '代金券说明', 'help' => '' ],
				'amount'      => [ 'title' => '代金券总数量', 'help' => '此设置项设置代金券的总发行数量。' ],
				'limit'       => [ 'title' => '每人可使用数量', 'help' => '此设置项设置每个用户可领取此代金券数量。' ],
			];
		}
		$field = Db::table( 'ticket' )->find( $tid );
		//所有会员组
		$groups = service( 'site' )->getSiteGroups();
		//卡券可使用的模块
		$modules = service( 'ticket' )->getTicketModules( $tid );
		//卡券可使用的会员组
		$groupsIds = service( 'ticket' )->getTicketGroupIds( $tid );
		View::with( [
			'msg'       => $msg,
			'field'     => $field,
			'groups'    => $groups,
			'modules'   => $modules,
			'groupsIds' => $groupsIds
		] );

		return view( $this->template . '/post.html' );
	}

	//删除卡券
	public function doSiteDel() {
		$tid = Request::get( 'tid' );
		$res = Db::table( 'ticket_record' )->where( 'siteid', SITEID )->where( 'tid', $tid )->get();
		if ( $res ) {
			message( '卡券已经使用,不能删除', 'back', 'error' );
		}
		Db::table( 'ticket' )->where( 'siteid', SITEID )->where( 'tid', $tid )->delete();
		message( '卡券删除成功', 'back', 'success' );
	}

	//卡券核销列表
	public function doSitecharge() {
		//分页数据
		$db = Db::table( 'member' );
		$db->field( "ticket_record.id,member.uid,ticket.title,ticket.thumb,ticket.condition,ticket.discount,user.username,ticket_record.createtime,ticket_record.usetime,ticket_record.status" );
		$db->join( 'ticket_record', 'member.uid', '=', 'ticket_record.uid' );
		$db->join( 'ticket', 'ticket.tid', '=', 'ticket_record.tid' );
		$db->leftJoin( 'user', 'user.uid', '=', 'ticket_record.manage' );
		$db->where( 'ticket.type', q( 'get.type' ) )->where( 'member.siteid', SITEID );
		//有搜索条件
		if ( $tid = q( 'get.tid' ) ) {
			$db->where( 'ticket.tid', $tid );
		}
		if ( $status = q( 'get.status' ) ) {
			$db->where( 'ticket_record.status', $status );
		}
		if ( $uid = q( 'get.uid' ) ) {
			$db->where( 'ticket_record.uid', $uid );
		}
		$page = Page::row( 15 )->make( $db->count() );
		//查询数据
		$db = Db::table( 'member' );
		$db->field( "ticket_record.id,member.uid,ticket.title,ticket.thumb,ticket.condition,ticket.discount,user.username,ticket_record.createtime,ticket_record.usetime,ticket_record.status" );
		$db->join( 'ticket_record', 'member.uid', '=', 'ticket_record.uid' );
		$db->join( 'ticket', 'ticket.tid', '=', 'ticket_record.tid' );
		$db->leftJoin( 'user', 'user.uid', '=', 'ticket_record.manage' );
		$db->where( 'ticket.type', q( 'get.type' ) )->where( 'member.siteid', SITEID );
		//有搜索条件
		if ( $tid = q( 'get.tid' ) ) {
			$db->where( 'ticket.tid', $tid );
		}
		if ( $status = q( 'get.status' ) ) {
			$db->where( 'ticket_record.status', $status );
		}
		if ( $uid = q( 'get.uid' ) ) {
			$db->where( 'ticket_record.uid', $uid );
		}
		$data = $db->limit( Page::limit() )->get();
		//卡券数据
		$ticket = Db::table( 'ticket' )->where( 'siteid', SITEID )->where( 'type', q( 'get.type' ) )->groupBy( 'tid' )->get();
		View::with( 'ticket', $ticket );
		View::with( 'data', $data );
		View::with( 'page', $page );
		return view( $this->template . '/charge.html' );
	}

	//核销卡券
	public function doSiteVerification() {
		$id    = q( 'get.id', 0, 'intval' );
		$model = new TicketRecord();
		if ( $model->verification( $id ) ) {
			message( '卡券核销成功', 'back', 'success' );
		}
		message( $model->getError(), 'back', 'error' );
	}

	//删除会员卡券兑换记录
	public function doSiteRemove() {
		$id    = q( 'get.id', 0, 'intval' );
		$model = new TicketRecord();
		if ( $model->delete( $id ) ) {
			message( '卡券删除成功', 'back', 'success' );
		}
		message( $model->getError(), 'back', 'error' );
	}
}











