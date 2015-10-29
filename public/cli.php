<?php
/**
 * cli方式执行请求
 * 目前可以模拟get
 *
 * php cli.php "request_uri=/index/index/testcli/p1/helloworld/p2/god" 
 */
define('APPLICATION_PATH', dirname(dirname(__FILE__)));

require APPLICATION_PATH . '/vendor/autoload.php';

$application = new Yaf\Application(APPLICATION_PATH . "/conf/application.ini");

//$param: method, module, controller, action, params
$request = new Yaf\Request\Simple();

Yaf\Dispatcher::getInstance()->dispatch($request);
