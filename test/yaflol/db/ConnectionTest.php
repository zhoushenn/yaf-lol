<?php

/**
 * @author: zhouwenlong
 * @since: 2016/1/18
 */
use yol\db\Connection;
class ConnectionTest extends PHPUnit_Framework_TestCase
{

    public function testGetPDO()
    {
        $dbConfig = \Yaf\Application::app()->getConfig()->toArray();
        $dbConfig = $dbConfig['db'];
        $con = new Connection($dbConfig);
        $pdo = $con->getPDO('slave');
        $this->assertInstanceOf('\PDO', $pdo);

        $con->query('set names utf8');

        $this->assertEquals($con->getDbInfo()['dbType'], 'slave');
    }

}
