<?php
/**
 * Created by PhpStorm.
 * User: zhouwenlong_91
 * Date: 2015/10/19
 */

class ConnectionTest extends PHPUnit_Framework_TestCase {

    static $db;
    static $dbConfig;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $dbConfig = [
            'share' =>  [
                'username' => 'root',
                'password' => '',
                'dbname'   =>'test',
                'tb_prefix' => 'god_',
                'db_type'   => 'mysql',
                'charset'   => 'utf8',
            ],
            'master' => [
                'host' => '127.0.0.1',
            ],
            'slaves' => [
                ['host' => '127.0.0.2'],
                ['host' => '127.0.0.3'],
                ['host' => '127.0.0.4'],
            ],
        ];

        self::$dbConfig = $dbConfig;

        self::$db = new Extend\zsdbal\Connection($dbConfig);
    }

    public function testGetDb()
    {
        $masterdb = self::$db->getDb('master');
        $this->assertInstanceOf('PDO', $masterdb);
        $this->assertEquals(self::$db->getCurrentConfig(), array_merge(self::$dbConfig['share'], self::$dbConfig['master']));

        $db = self::$db->getDb('slaves');
        $this->assertInstanceOf('PDO', $db);
        $this->assertNotEquals(self::$db->getCurrentConfig()['host'], self::$dbConfig['master']['host']);
    }


}
