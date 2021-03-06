<?php namespace module\button;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

use module\hdSite;

/**
 * 微信菜单
 * Class site
 * @package module\menu
 * @author 向军
 */
class site extends hdSite {
	protected $db;

	public function __construct() {
		parent::__construct();
		$this->db = new \system\model\Button();
	}

	//菜单列表
	public function doSiteLists() {
		$data = $this->db->where( 'siteid', SITEID )->get();
		View::with( 'data', $data );

		return view( $this->template . '/lists.html' );
	}

	//删除菜单
	public function doSiteRemove() {
		$id     = q( 'get.id' );
		$status = $this->db->where( 'id', $id )->where( 'siteid', SITEID )->delete();
		if ( $status ) {
			message( '删除菜单成功', 'back', 'success' );
		}
		message( '删除菜单失败', 'back', 'error' );
	}

	//推送到微信端
	public function doSitePushWechat() {
		$id   = q( 'get.id' );
		$data = $this->db->where( 'id', $id )->pluck( 'data' );
		$data = json_decode( $data, TRUE );
		$data = $this->addHttp( $data );
		$res  = \Weixin::instance( 'button' )->createButton( $data );
		if ( $res['errcode'] == 0 ) {
			$this->db->whereNotIn( 'id', [ $id ] )->update( [ 'status' => 0 ] );
			$this->db->where( 'id', $id )->update( [ 'status' => 1 ] );
			message( '推送微信菜单成功', 'back', 'success' );
		}
		message( $res['errinfo'], 'back', 'error', 5 );
	}

	//添加/编辑菜单
	public function doSitePost() {
		$id = q( 'get.id' );
		if ( IS_POST ) {
			$this->db['title']  = Request::post( 'title' );
			$this->db['data']   = Request::post( 'data' );
			$this->db['status'] = 0;
			$this->db['id']     = Request::get( 'id' );
			$this->db->save();
			message( '添加菜单成功', site_url( 'lists' ), 'success' );
		}
		if ( $id ) {
			$field = Db::table( 'button' )->where( 'id', $id )->first();
		} else {
			$field['title'] = '';
			$data           = [ 'button' => [ [ 'type' => 'view', 'name' => '菜单名称', 'url' => '', 'sub_button' => [ ] ] ] ];
			$field['data']  = json_encode( $data, JSON_UNESCAPED_UNICODE );
		}

		return view( $this->template . '/post.html' )->with( 'field', $field );
	}

	//url地址前添加http
	protected function addHttp( $data ) {
		foreach ( $data['button'] as $k => $v ) {
			//添加url前缀
			if ( isset( $v['url'] ) && ! preg_match( '/^http/', $v['url'] ) ) {
				$data['button'][ $k ]['url'] = __ROOT__ . $v['url'];
			}
			//子菜单处理
			if ( isset( $v['sub_button'] ) ) {
				foreach ( $v['sub_button'] as $n => $m ) {
					if ( isset( $m['url'] ) && ! preg_match( '/^http/', $m['url'] ) ) {
						$data['button'][ $k ]['sub_button'][ $n ]['url'] = __ROOT__ . $m['url'];
					}
				}
			}
		}

		return $data;
	}

}