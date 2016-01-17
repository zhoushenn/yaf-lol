<?php
namespace yol\db;
/**
 * DbQuery查询构造组件
 * 
 * $query = new DbQuery(new PDO(...));
 * $query->from()->join()->join()->where()->group()->order()->limit()->select();
 * @author: zhouwenlong
 * @since: 2016/1/14
 */
class DbQuery
{
    protected $stack = [];
    private $db;
    public $logs = [];

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function quote($str)
    {
        return $this->db->quote($str);
    }

    public function select()
    {
        $sql = implode(' ', $this->stack);
        $this->logs[] = $sql;
        return $this->db->query($sql);
    }

    public function execute()
    {
        $sql = implode(' ', $this->stack);
        $this->logs[] = $sql;
        return $this->db->exec($sql);
    }

    protected function from($table, array $fields)
    {
        $this->stack[] = sprintf('SELECT %s FROM %s', implode(',', $fields), $table);
        return $this;
    }

    protected function join($table, $joinType, $condition = 'onConditionString')
    {
        $this->stack[] = sprintf('%s %s ON %s', strtoupper($joinType), $table, $condition);
        return $this;
    }

    protected function update($table, array $sets)
    {
        $this->stack[] = sprintf('UPDATE %s %s', $table, $this->setCondition($sets));
        return $this;
    }

    protected function delete($table)
    {
        $this->stack[] = sprintf('DELETE FROM %s', $table);
        return $this;
    }

    protected function insert($table, array $fields)
    {
        $this->stack[] = sprintf('INSERT INTO %s(%s)', $table, implode(',', $fields));
        return $this;
    }

    protected function replace($table, array $fields)
    {
        $this->stack[] = sprintf('REPLACE INTO %s(%s)', $table, implode(',', $fields));
        return $this;
    }

    protected function values(array $values)
    {
        $this->stack[] = sprintf( 'VALUES(%s)', implode( ',', array_map([$this, 'quote'], $values) ) );
        return $this;
    }

    protected function set(array $sets)
    {
        $this->stack[] = sprintf( 'SET %s', implode( ',', array_map([$this, 'quote'], $sets) ) );
        return $this;
    }

    protected function where(array $where)
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

    protected function group(array $group)
    {
        $this->stack[] = sprintf('GROUP BY %s', implode(',', $group));
        return $this;
    }

    protected function order(array $group)
    {
        $this->stack[] = sprintf('ORDER BY %s', implode(',', $group));
        return $this;
    }

    protected function limit(array $limit)
    {
        $this->stack[] = sprintf('LIMIT %s', implode(',', $limit));
        return $this;
    }
}