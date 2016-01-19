<?php

/**
 * @author: zhouwenlong
 * @since: 2016/1/19
 */
class RulesTest extends PHPUnit_Framework_TestCase
{

    public function testValid_require()
    {
        $e = \yol\validator\Rules::valid_require('');
        $this->assertEquals($e, '不能留空');
    }

    public function testValid_mobile()
    {
        $e = \yol\validator\Rules::valid_mobile(12345678901);
        $this->assertEquals($e, '不是有效的手机格式');
    }
}
