<?php
/**
 * @author: zhouwenlong
 * @since: 2016/1/14
 */

namespace yol\db;

abstract class QueryAbstract
{
    protected $logs = [];

    abstract public function insert($table, $data);
    abstract public function delete($table, $where);
    abstract public function update($table, $where);
    public function select($table, $field, $where)
    {
    }

    protected function builtSelectField(array $fields)
    {
        return implode(',', $fields);
    }

    public function sqlLogs(){
        return $this->logs;
    }
}