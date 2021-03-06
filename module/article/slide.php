<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace module\article;

use module\hdSite;
use system\model\Web;
use system\model\WebSlide;

/**
 * 站点幻灯图
 * Class slide
 * @package module\article
 */
class slide extends hdSite {
	//官网编号
	protected $webid;
	protected $web;
	protected $webSlide;

	public function __construct() {
		parent::__construct();
		$this->web      = new Web();
		$this->webSlide = new WebSlide();
		//官网编号
		$this->webid = Request::get( 'webid' );
		if ( ! $this->web->where( 'id', $this->webid )->get() ) {
			message( '你访问的官网不存在', 'back', 'error' );
		}
	}

	//列表
	public function doSiteLists() {
		if ( IS_POST ) {
			foreach ( Request::post( 'slide' ) as $id => $order ) {
				$this->webSlide['displayorder'] = $order;
				$this->webSlide['id']           = $id;
				$this->webSlide->save();
			}
			message( '修改幻灯片成功', 'refresh', 'success' );
		}
		$data = $this->webSlide->where( 'web_id', $this->webid )->get();
		View::with( 'data', $data );

		return View::make( $this->template . '/slide/lists.php' );
	}

	//添加&修改
	public function doSitePost() {
		$slideId = Request::get( 'id' );
		if ( IS_POST ) {
			$this->webSlide['web_id'] = $this->webid;
			$this->webSlide['id']     = $slideId;
			foreach ( Request::post() as $k => $v ) {
				$this->webSlide[ $k ] = $v;
			}
			$this->webSlide->save();
			message( '幻灯片保存成功', site_url( 'lists', [ 'webid' => $this->webid ] ), 'success' );
		}
		$field = $this->webSlide->find( $slideId );
		//官网列表
		$web = $this->web->find( $this->webid );
		View::with( 'field', $field );
		View::with( [ 'web' => $web ] );

		return View::make( $this->template . '/slide/post.php' );
	}

	//删除
	public function doSiteRemove() {
		$this->webSlide->delete( Request::get( 'id' ) );
		message( '删除幻灯片成功', 'back', 'success' );
	}
}