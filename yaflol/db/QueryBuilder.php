<?php
namespace yol\db;

use \sprintf;

/**
 * 基于mysql的sql语句生成类，如果使用其他数据库，可能需要继承然后覆盖不兼容的类。
 *
 * @author: zhouwenlong
 * @since: 2016/1/14
 */
abstract class QueryBuilder
{
    abstract function quote($str);

    public $sql = '';

    public function builtInsertSql($table, array $data, array $otherCondition = [])
    {
        $sql = '';
        $sql .= $this->insertCondition($table, array_keys($data));
        $sql .= $this->valuesCondition($data);
        $sql .= $this->builtOtherConditionString($otherCondition);
        return $sql;
    }

    public function builtDeleteSql($table, array $where, array $otherCondition = [])
    {
        $sql = '';
        $sql .= $this->deleteCondition($table);
        $sql .= $this->whereCondition($where);
        $sql .= $this->builtOtherConditionString($otherCondition);
        return $sql;
    }

    public function builtUpdateSql($table, array $data, array $where = [], array $otherCondition = [])
    {
        $sql = '';
        $sql .= $this->updateCondition($table, $data);
        $sql .= $this->whereCondition($where);
        $sql .= $this->builtOtherConditionString($otherCondition);
        return $sql;
    }

    public function builtSelectSql($table, array $field, array $where = [], array $otherCondition = [])
    {
        $sql  = '';
        $sql .= $this->selectCondition($table, $field);
        $sql .= $this->whereCondition($where);
        $sql .= $this->builtOtherConditionString($otherCondition);
        return $sql;
    }

    protected function builtOtherConditionString(array $otherCondition)
    {
        $sql = '';
        if( isset($otherCondition['group']) ){
            $sql .= $this->groupCondition($otherCondition['group']);
        }
        if( isset($otherCondition['order']) ){
            $sql .= $this->orderCondition($otherCondition['order']);
        }
        if( isset($otherCondition['limit']) ){
            $sql .= $this->limitCondition($otherCondition['limit']);
        }

        return $sql;
    }

    protected function selectCondition($table, array $fields)
    {
        return sprintf('SELECT %s FROM %s ', implode(',', $fields), $table);
    }

    protected function joinCondition($table, $joinType, $condition = 'onConditionString')
    {
        return sprintf(' %s %s ON %s', strtoupper($joinType), $table, $condition);
    }

    protected function updateCondition($table, array $sets)
    {
        return sprintf('UPDATE %s %s ', $table, $this->setCondition($sets));
    }

    protected function deleteCondition($table)
    {
        return sprintf('DELETE FROM %s ', $table);
    }

    protected function insertCondition($table, array $fields)
    {
        return sprintf('INSERT INTO %s(%s) ', $table, implode(',', $fields));
    }

    protected function replaceCondition($table, array $fields)
    {
        return sprintf('REPLACE INTO %s(%s) ', $table, implode(',', $fields));
    }

    protected function valuesCondition(array $values)
    {
        return sprintf( 'VALUES(%s) ', implode( ',', array_map([$this, 'quote'], $values) ) );
    }

    protected function setCondition(array $sets)
    {
        return sprintf( ' SET %s ', implode( ',', array_map([$this, 'quote'], $sets) ) );
    }

    protected function whereCondition(array $where)
    {
        if(empty($where)) return null;

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
        return sprintf(' WHERE %s ', $whereString);
    }

    protected function groupCondition(array $group)
    {
        return sprintf(' GROUP BY %s ', implode(',', $group));
    }

    protected function orderCondition(array $group)
    {
        return sprintf(' ORDER BY %s', implode(',', $group));
    }

    protected function limitCondition(array $limit)
    {
        return sprintf(' LIMIT %s', implode(',', $limit));
    }
}