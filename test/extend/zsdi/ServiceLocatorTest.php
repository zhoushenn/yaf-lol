<?php
/**
 * Created by PhpStorm.
 * User: zhouwenlong_91
 * Date: 2015/10/15
 */

class ServiceLocatorTest extends PHPUnit_Framework_TestCase {

    static $serviceLocator;

    //基境
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$serviceLocator = new Extend\zsdi\ServiceLocator;

    }

    protected function setUp()
    {
        parent::setUp();
    }

    public function testSet()
    {

        $defind = function(){
            return new SomeServiceForTest;
        };

        self::$serviceLocator->set('db', $defind);

        $defintion = self::$serviceLocator->getDefintions();

        $this->assertEquals($defintion['db'], $defind);
    }

    public function test__get()
    {
        $get = self::$serviceLocator->get('db');
        $this->assertInstanceOf('SomeServiceForTest', $get);
    }

    public function testGet()
    {
        $get = self::$serviceLocator->get('db');
        $this->assertInstanceOf('SomeServiceForTest', $get);
    }

    public function testHas()
    {
        $this->assertEquals(true, self::$serviceLocator->has('db'));
    }

    public function testGetComponents()
    {
        $components = self::$serviceLocator->getComponents();
        $this->assertNotEmpty($components);
    }

    public function testGetDefintions()
    {
        $defitions = self::$serviceLocator->getDefintions();
        $this->assertNotEmpty($defitions);
    }

    public function testClose()
    {
        self::$serviceLocator->close('db');

        $false = self::$serviceLocator->get('db');
        $this->assertFalse($false);
    }

}

class SomeServiceForTest{

}
