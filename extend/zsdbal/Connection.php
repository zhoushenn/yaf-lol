<?php
namespace Extend\zsdbal;

use PDO;

/**
 * Mysql数据库连接类，实现读写分离
 *
 * 数据库配置示例
 * [
        //通用配置
        'share' => [
            'username' => 'root',
            'password' => '',
            'dbname'   => 'test',
            'tb_prefix' => 'god_',
            'charset' => 'utf8',
            'type' => 'mysql',
        ],
        //主库
        'master' => [
            'host' => '127.0.0.1',
        ],
        //从库
        'slaves' => [
            [
            'host' => '127.0.0.3',
            ],
            [
            'host' => '127.0.0.2',
            ],
        ]
    ];
 *
 * @package Extend\zsdbal
 * @author zhoushen <445484792@qq.com>
 * @since 1.0
 */
class Connection{

    /**
     * @var array 数据库配置
     */
    protected $dbConfig;
    /**
     * @var array 实例化的数据库对象
     */
    protected $dbPools;
    /**
     * @var array 当前使用的配置
     */
    protected $currentConfig;
    /**
     * @var string 主或从 master|slaves
     */
    protected $type = 'master';

    /**
     * @param array $dbConfig 数据库配置
     */
    public function __construct(array $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    /**
     * 获取指定类型的数据据库对象
     *
     * @param string $type master|slaves
     * @return PDO
     */
    public function getDb($type){
        if(isset($this->dbPools[$type])){
            return $this->dbPools[$type];
        }

        $this->type = $type;
        $this->createConfig();
        return $this->dbPools[$type] = $this->createDb();
    }

    /**
     * 获取生成的数据库配置
     *
     * @return mixed array|null
     */
    public function getCurrentConfig()
    {
        return isset($this->currentConfig) ? $this->currentConfig : null;
    }

    /**
     * 生成当前使用数据库配置信息
     *
     * @return array
     */
    protected function createConfig()
    {
        $config = $this->dbConfig[$this->type];

        if($this->type == 'slaves'){
            $config = $this->dbConfig['slaves'][ array_rand($this->dbConfig['slaves']) ];
        }

        return $this->currentConfig = array_merge($config, $this->dbConfig['share']);
    }

    /**
     * 创建数据库对象
     *
     * @return PDO
     */
    protected function createDb(){
        $config = $this->currentConfig;
        $dsn    = "{$config['db_type']}:charset={$config['charset']};dbname={$config['dbname']};host={$config['host']}";
        $db     =  new PDO($dsn, $config['username'], $config['password']);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $db;
    }

    /**
     * 调用pdo对象方法
     *
     * @param $name pdo方法
     * @param array $arguments
     * @return mixed
     */
    function __call($name, $arguments=[])
    {

        if($name == 'exec' || ($name == 'prepare' && substr($arguments[0], 0, 6) != 'select')){
            $type = 'master';
        }else{
            $type = 'slaves';
        }

        $db = $this->getDb($type);
        //add table prefix
        if($name == 'exec' || $name == 'query' || $name == 'prepare'){
            $arguments[0] = str_replace('prefix_', $this->dbConfig['share']['tb_prefix'], $arguments[0]);
        }
        return call_user_func_array(array($db, $name), $arguments);
    }

    /**
     * 关闭数据库
     *
     * @param string $type master|slaves
     */
    public function closeDb($type = null){
        if($type){
            unset($this->dbPools[$type]);
        }else{
            unset($this->dbPools);
        }
    }

}