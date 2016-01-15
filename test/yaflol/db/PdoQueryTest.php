<?php

/**
 * @author: zhouwenlong
 * @since: 2016/1/15
 */
class PdoQueryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \yol\db\PdoQuery
     */
    static $db;
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$db = new \yol\db\PdoQuery(new \PDO('mysql:host=localhost;dbname=test', 'root', ''));
    }


    public function testBuiltInsertSql()
    {
        $sql = self::$db->builtInsertSql('cp_user_test',
            ['name' => 'zhoushen', 'email' => 'hello'],
            [
                'group' => ['name', 'email'],
                'order' => ['name desc', 'email asc'],
                'limit' => [1, 100],
            ]);

        $expected = 'INSERT INTO cp_user_test(name,email) VALUES(\'zhoushen\',\'hello\')  GROUP BY name,email  ORDER BY name desc,email asc LIMIT 1,100';
        $this->assertEquals($expected, $sql);
    }

    public function testBuiltSelectSql()
    {
        $sql = self::$db->builtSelectSql('cp_user_test', ['*'],
            ['name' => ['=', 'admin'], 'and ctime' => ['between', '1', '10000000'], 'or id' => ['in', [46, 17] ] ],
            [
                'group' => ['name', 'email'],
                'order' => ['name desc', 'email asc'],
                'limit' => [1, 100],
            ]);
        $expedted = 'SELECT * FROM cp_user_test  WHERE  name = \'admin\' and ctime BETWEEN \'1\' AND \'10000000\' or id IN(\'46\',\'17\')  GROUP BY name,email  ORDER BY name desc,email asc LIMIT 1,100';
        $this->assertEquals($expedted, $sql);
    }

    public function testBuiltDeleteSql()
    {
        $sql = self::$db->builtDeleteSql('cp_user_test', ['name'=>['like', '%xxx%'], 'and email' => ['=', 'bbb'] ]);
        $e = "DELETE FROM cp_user_test  WHERE  name LIKE '%xxx%' and email = 'bbb' ";
        $this->assertEquals($e, $sql);
    }

    public function testBuiltUpdateSql()
    {
        $sql = self::$db->builtUpdateSql('cp_user_test', ['name'=>'zhoushen', 'email' => 'xxxx' ], []);
        $e = "UPDATE cp_user_test  SET 'zhoushen','xxxx'  ";
        $this->assertEquals($e, $sql);
    }


}
