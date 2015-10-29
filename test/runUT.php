<?php
/**
 * Created by PhpStorm.
 * User: zhouwenlong_91
 * Date: 2015/10/15
 * 手动跑单元测试 (临时开发用)
 * only support run with cli.
 */

define('TEST_PATH', '../test/');
//echo PHPUNIT_PATH;die;

$command = "..\\vendor\\bin\\phpunit --verbose " . TEST_PATH;

system($command);