<?php
/**
 * @author: zhouwenlong
 * @since: 2016/1/15
 */

namespace yol\db;


class MysqliQuery extends QueryBuilder implements QueryInterface
{
    /**
     * @var \Mysqli
     */
    protected $db;
    public $logs = [];
    public function __construct(\Mysqli $mysqli)
    {
        $this->db = $mysqli;
    }

    function quote($str)
    {
        return $this->db->real_escape_string($str);
    }

    public function sqlTrace()
    {
        return $this->logs;
    }

    public function query($sql)
    {
        return $this->db->query($sql);
    }

    public function exec($sql)
    {
        return $this->db->exec($sql);
    }


}