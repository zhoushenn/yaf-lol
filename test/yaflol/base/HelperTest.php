<?php
/**
 * @author: zhouwenlong
 * @since: 2016/1/14
 */
class HelperTest extends PHPUnit_Framework_TestCase
{

    public function testUrl()
    {
        $request = new Yaf\Request\Simple('get', 'index', 'index', 'index', []);
        Yaf\Dispatcher::getInstance()->setRequest($request);
        $actual = yol\base\Helper::url('index');
        $this->assertEquals('/index/index/index', $actual);
    }

    public function testGetConfig()
    {
        $actual = yol\base\Helper::getConfig('application')->toArray();
        $this->assertArrayHasKey('log', $actual);
    }

}
