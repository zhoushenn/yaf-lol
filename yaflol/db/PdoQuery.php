<?php
/**
 * @author: zhouwenlong
 * @since: 2016/1/14
 */

namespace yol\db;

class PdoQuery extends QueryAbstract
{
    public function __construct(\PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function select($table, $field, $where)
    {
    }
}