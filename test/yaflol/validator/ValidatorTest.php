<?php

/**
 * @author: zhouwenlong
 * @since: 2016/1/19
 */
class ValidatorTest extends PHPUnit_Framework_TestCase
{

    public function test()
    {
        $validator = new \yol\validator\Validator();
        $result = $validator->validate([
            'name' => 'zhoushen',
            'email' => '44@qq.com',
        ],[
            'name|用户名' => 'require|maxlen(10)',
            'email|用户邮箱' => 'require|email',
        ]);

        $this->assertEquals(true, $result);
    }
}
