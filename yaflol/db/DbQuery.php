<?php
namespace yol\db;
/**
 * DbQuery查询构造组件
 *
 * @example:
 * $query = new DbQuery( new Connection(...) );
 * $result = $query->from()->join()->join()->where()->group()->order()->limit()->select();
 *
 * @package: yol\db
 * @author: zhouwenlong
 * @since: 2016/1/14
 */
class DbQuery
{
    /**
     * @var array sql语句
     */
    protected $stack = [];
    /**
     * @var \yol\db\Connection 数据库连接管理（代理pdo）
     */
    private $connection;
    /**
     * @var array 执行过的sql
     */
    public $logs = [];

    /**
     * DbQuery constructor.
     * @param Connection $connection
     */
    public function __construct(\yol\db\Connection $connection)
    {
        $this->connection = $connection;
    }

    public function quote($str)
    {
        $pdo = $this->connection->getPDO(Connection::SLAVE_NAME);
        return $pdo->quote($str);
    }

    public function stackToString()
    {
        return implode(' ', $this->stack);
    }

    public function log()
    {
        return $this->logs;
    }

    /**
     * 原生sql语句执行
     * @param $sql prepare语句
     * @param array $bindParams 参数
     * @return bool
     */
    public function execRaw($sql, array $bindParams)
    {
        $pdo = $this->connection->getPDO(Connection::MASTER_NAME);
        $stm = $pdo->prepare($sql);
        return $stm->execute($bindParams);
    }

    /**
     * 原生sql语句查询
     * @param $sql prepare语句
     * @param array $bindParams 参数
     * @return bool
     */
    public function queryRaw($sql, array $bindParams)
    {
        $pdo = $this->connection->getPDO(Connection::SLAVE_NAME);
        $stm = $pdo->prepare($sql);
        $result = $stm->execute($bindParams);
        if($result === false){
            return false;
        }
        return $stm;
    }


    /**
     * 查询sql
     * @return \PDOStatement
     */
    public function select()
    {
        $sql = $this->stackToString();
        $this->logs[] = $sql;
        $pdo = $this->connection->getPDO(Connection::SLAVE_NAME);
        return $pdo->query($sql);
    }

    /**
     * 执行sql
     * @return int
     */
    public function execute()
    {
        $sql = $this->stackToString();
        $this->logs[] = $sql;
        $pdo = $this->connection->getPDO(Connection::MASTER_NAME);
        return $pdo->exec($sql);
    }

    public function from($table, array $fields)
    {
        $this->stack = [];
        $this->stack[] = sprintf('SELECT %s FROM %s', implode(',', $fields), $table);
        return $this;
    }

    public function join($table, $joinType, $onString)
    {
        $this->stack[] = sprintf('%s %s ON %s', strtoupper($joinType), $table, $onString);
        return $this;
    }

    public function update($table, array $sets)
    {
        $this->stack = [];
        $this->stack[] = sprintf('UPDATE %s %s', $table, $this->setCondition($sets));
        return $this;
    }

    public function delete($table)
    {
        $this->stack = [];
        $this->stack[] = sprintf('DELETE FROM %s', $table);
        return $this;
    }

    public function insert($table, array $fields)
    {
        $this->stack = [];
        $this->stack[] = sprintf('INSERT INTO %s(%s)', $table, implode(',', $fields));
        return $this;
    }

    public function replace($table, array $fields)
    {
        $this->stack = [];
        $this->stack[] = sprintf('REPLACE INTO %s(%s)', $table, implode(',', $fields));
        return $this;
    }

    public function values(array $values)
    {
        $this->stack[] = sprintf( 'VALUES(%s)', implode( ',', array_map([$this, 'quote'], $values) ) );
        return $this;
    }

    public function set(array $sets)
    {
        $this->stack[] = sprintf( 'SET %s', implode( ',', array_map([$this, 'quote'], $sets) ) );
        return $this;
    }

    public function where(array $where)
    {
        $whereString = '';
        foreach($where as $field => $valueField){

            $_opt = strtoupper($valueField[0]);

            if( stripos('|=|>|<|BETWEEN|IN|NOT IN|LIKE', $_opt) === false ) {
                throw new DbException('Invalid operator ' . $_opt);
            }

            if( $_opt == 'BETWEEN' ){
                $whereString .= ' ' . $field . ' BETWEEN ' . $this->quote($valueField[1]) . ' AND ' . $this->quote($valueField[2]);
            }elseif( $_opt == 'IN' || $_opt == 'NOT IN' ){
                $whereString .= ' ' . $field . ' ' . $_opt . '(' . implode( ',', array_map([$this, 'quote'], $valueField[1]) ) . ')';
            }else{
                $whereString .= ' ' . $field . ' ' . $_opt . ' ' . $this->quote($valueField[1]);
            }
        }
        $this->stack[] = sprintf('WHERE%s', $whereString);
        return $this;
    }

    public function group(array $group)
    {
        $this->stack[] = sprintf('GROUP BY %s', implode(',', $group));
        return $this;
    }

    public function order(array $group)
    {
        $this->stack[] = sprintf('ORDER BY %s', implode(',', $group));
        return $this;
    }

    public function limit(array $limit)
    {
        $this->stack[] = sprintf('LIMIT %s', implode(',', $limit));
        return $this;
    }
}