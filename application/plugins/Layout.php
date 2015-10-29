<?php
/**
 * 布局插件
 * 用来控制器渲染视图的时候使用布局
 *
 * @author zhoushen
 * @since 2015/10/14
 */
class LayoutPlugin extends Yaf\Plugin_Abstract {

	public function dispatchLoopShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
		$response->setBody( call_user_func( $response->layout, $response->getBody() ) );
	}
}
