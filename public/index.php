<?php
/**
 * web 入口
 */
header("Content-type: text/html; charset=utf-8");

define('APPLICATION_PATH', dirname(dirname(__FILE__)));

require APPLICATION_PATH . '/vendor/autoload.php';

$application = new Yaf\Application( APPLICATION_PATH . "/conf/application.ini");

$application->bootstrap()->run();
