<?php
//pretty display debug info
//accross throw exception | trigger user error to show debug info
class DebugPlugin extends Yaf\Plugin_Abstract {

	public function routerStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
		$run     = new \Whoops\Run();
		$handler = new \Whoops\Handler\PrettyPageHandler();
		$run->pushHandler($handler);
		$run->register();
	}
}
