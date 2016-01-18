<?php
namespace yol\plugins;
use Yaf;
use yol;

/**
 * pretty display debug info
 * Class DebugPlugin
 * @author zhoushen
 * @since 2016/01/12
 */
class Debug extends Yaf\Plugin_Abstract {

	public function routerStartup(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
        error_reporting(E_ERROR | E_PARSE);
        ini_set('display_errors', true);
        /**
         * phpConsole调试(需要安装google扩展)
         * 可以直接调用phpConsole客户端，或者monolog也封装了客户端
         * phpConsole php client: https://github.com/barbushin/php-console/wiki/PHP-Console-extension-features
         * monolog phpConsole client:https://github.com/Seldaek/monolog/blob/master/src/Monolog/Handler/PHPConsoleHandler.php
         */
        $logger = new \Monolog\Logger('all', array(new \Monolog\Handler\PHPConsoleHandler()));
        //TODO::找到关闭气泡弹窗方法。
        \Monolog\ErrorHandler::register($logger);
	}

	public function dispatchLoopShutdown(Yaf\Request_Abstract $request, Yaf\Response_Abstract $response) {
        /**
         * phpConsole调试(需要安装google扩展)
         */
		\PC::debug( yol\di\Container::db()->log(), 'sql.trace');
        $performance = [
            'memory_usage' => memory_get_usage() - APP_MEMORY_START,
            'time_usage'   => microtime(true)    - APP_MICROTIME_START,
        ];
        \PC::debug($performance, 'performance.info');
	}
}
