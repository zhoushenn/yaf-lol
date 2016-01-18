<?php
/**
 * @author: zhouwenlong
 * @since: 2016/1/18
 */
class DbQueryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var yol\db\DbQuery
     */
    static $db;
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        $dbConfig = Yaf\Application::app()->getConfig()->toArray();
        $dbConfig = $dbConfig['db'];
        self::$db = new yol\db\DbQuery(new yol\db\Connection($dbConfig));

    }

    public function testQuote()
    {
        $str = "hello'wrold";
        $ac = self::$db->quote($str);

        $this->assertEquals("'hello\\'wrold'", $ac);
    }

    public function testSelect()
    {
        self::$db->from('cp_user_test', ['*'])->join('cp_user_test as ct1', 'inner join', 'cp_user_test.name=ct1.name')
                                              ->join('cp_user_test as ct2', 'left join', 'cp_user_test.name=ct2.name')
                                              ->where(['cp_user_test.email' =>['=', 'ct1.name']])
                                              ->group(['name'])
                                              ->order(['email desc'])
                                              ->limit([1,2]);
        $e = 'SELECT * FROM cp_user_test INNER JOIN cp_user_test as ct1 ON cp_user_test.name=ct1.name LEFT JOIN cp_user_test as ct2 ON cp_user_test.name=ct2.name WHERE cp_user_test.email = \'ct1.name\' GROUP BY name ORDER BY email desc LIMIT 1,2';
        $this->assertEquals( $e, self::$db->stackToString() );
    }

    public function testExecute()
    {
        $e = 'INSERT INTO cp_user_test(name,email) VALUES(\'zhoushen\',\'44@qq.com\')';
        self::$db->insert('cp_user_test', ['name','email'])->values(['zhoushen','44@qq.com']);
        $this->assertEquals( $e, self::$db->stackToString());
    }


}
