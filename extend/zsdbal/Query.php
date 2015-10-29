<?php
namespace Extend\zsdbal;
use Yaf;

/**
 * 数据库操作类，基于pdo实现简单的curl封装
 *
 * @package Extend\zsdbal
 * @author zhoushen <445484792@qq.com>
 * @since 1.0
 */
class Query{

    /**
     * @var string 数据库表名（不含前缀）
     */
    protected $tableName;
    /**
     * @var Object Extend\zsdbal\Commection 数据库连接对象
     */
    protected $db;
    /**
     * @var array 执行过的sql
     */
    public $sqls;
    /**
     * @var int 执行的sql计数
     */
    public $count = 0;

    /**
     * @param null $db
     */
    public function __construct($db = null){
    	$db == null ? $this->db = Yaf\Application::app()->service->db : $this->db = $db;
    }

    /**
     * @return mixed
     */
    public function getTable(){
    	return $this->tableName;
    }

    /**
     * @param $v
     * @return mixed
     */
    public function quote($v){
    	return $this->db->quote($v);
    }

    /**
     * @param array $field
     * @param array $where
     * @param array $group
     * @param array $order
     * @param array $limit
     * @return bool
     */
    public function get($field = [], $where = [], $group = [], $order = [], $limit = [1]){
        $result = $this->select($field, $where, $group, $order, $limit);
        if($result === false){
            return $result;
        }
        return $result[0];
    }

    /**
     * @param array $field
     * @param array $where
     * @param array $group
     * @param array $order
     * @param array $limit
     * @return bool
     */
    public function select($field = [], $where = [], $group = [], $order = [], $limit = []){

    	$sql = 'SELECT ';
    	if($field){
    		$sql .= $this->builtField($field);
    	}else{
    		$sql .= '*';
    	}
    	$sql .= ' FROM ' . $this->getTable();
    	if($where){
    		$sql .= ' WHERE ' . $this->builtWhere($where);
    	}
    	if($group){
    		$sql .= ' GROUP BY ' . $this->builtGroup($group); 
    	}
    	if($order){
    		$sql .= ' ORDER BY ' . $this->builtOrder($order);
    	}
    	if($limit){
    		$sql .= ' LIMIT ' . $this->builtLimit($limit);
    	}

    	$stm = $this->query($sql);
    	return $stm === false ? $stm : $stm->fetchAll();
    }

    /**
     * @return mixed
     */
    public function debug(){
    	return $this->sqls;
    }

    /**
     *
     */
    public function __destruct(){
//        if(APP_DEBUG){
//            Yaf\Application::app()->debugInfo['sqlTrace'] = $this->debug();
//        }
    }

    /**
     * @param $command
     * @return bool
     */
    protected function exec($command){
    	$count = $this->count++;
    	$this->sqls[$count]['sql'] = $command;
    	$result = $this->db->exec($command);
    	if($result === false){
    		$this->sqls[$count]['error'] = $this->db->errorInfo();
            return false;
//    		throw new \Exception('exec command failture');
    	}
    	return $result;
    }

    /**
     * @param $query
     * @return bool
     */
    protected function query($query){
    	$count = $this->count++;
    	$this->sqls[$count]['sql'] = $query;
    	$stm = $this->db->query($query);
    	if($stm === false){
    		$this->sqls[$count]['error'] = $this->db->errorInfo();
            return false;
//            throw new \Exception('query command failture');
        }

    	return $stm;
    }

    /**
     * @param $field
     * @return string
     */
    protected function builtField($field){
    	return implode(',', $field);
    }

    /**
     * @param $where
     * @return string
     */
    protected function builtWhere($where){
    	$whereString = '';
    	foreach ($where as $key => $value) {
    		$whereString .=  $key . "=" . $this->quote($value) . ',';
    	}
    	return substr($whereString, 0, -1);
    }

    /**
     * @param $group
     * @return string
     */
    protected function builtGroup($group){
    	return implode(',', $group);
    }

    /**
     * @param $order
     * @return string
     */
    protected function builtOrder($order){
    	return implode(',', $order);
    }

    /**
     * @param $limit
     * @return string
     */
    protected function builtLimit($limit){
    	return implode(',', $limit);
    }

    /**
     * @param array $data
     * @param array $where
     * @return bool
     */
    public function update($data = [], $where = []){
    	$sql = 'UPDATE ' . $this->getTable() . ' SET ';
    	$sql .= $this->builtWhere($data);
    	if($where){
    		$sql .= ' WHERE ' . $this->builtWhere($where);
    	}
    	return $this->exec($sql);
    }

    /**
     * @param array $where
     * @param array $limit
     * @return bool
     */
    public function delete($where = [], $limit = []){
    	$sql = 'DELETE FROM ' . $this->getTable();
    	if($where){
	    	$sql .= ' WHERE ' . $this->builtWhere($where);
    	}
    	if($limit){
    		$sql .= ' LIMIT ' . $this->builtLimit($limit);
    	}
    	
    	return $this->exec($sql);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function insert($data = []){
    	$sql = 'INSERT INTO ' . $this->getTable();
    	$fieldString = '(' . implode(',', array_keys($data)) . ') ';
    	$sql .= $fieldString . 'values ';
    	$valueString = '(' . implode(',', array_map(array($this, 'quote'), $data)) . ')';
    	$sql .= $valueString;
    	return $this->exec($sql);
    }

    /**
     * 多条数据插入
     *
     * @param array $field
     * @param array $data
     * @return bool
     */
    public function multiInsert($field = [], $data = []){
    	$sql = 'INSERT INTO ' . $this->getTable();
    	$fieldString = '(' . implode(',', $field) . ') ';
    	$sql .= $fieldString . 'values ';
    	$valueString = '';
    	foreach ($data as $record) {
    		$valueString .= '(' . implode(',', array_map(array($this, 'quote'), $record)) . '),';
    	}
    	
    	$sql .= substr($valueString, 0, -1);
    	return $this->exec($sql);
    }
}