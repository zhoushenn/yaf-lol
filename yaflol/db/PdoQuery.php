<?php
/**
 * @author: zhouwenlong
 * @since: 2016/1/14
 */

namespace yol\db;

class PdoQuery extends QueryBuilder implements QueryInterface
{
    /**
     * @var \PDO
     */
    protected $db;
    public $logs = [];
    public function __construct(\PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function quote($str)
    {
        return $this->db->quote($str);
    }

    public function sqlTrace()
    {
        return $this->logs;
    }

    public function query($sql)
    {
        $this->logs[] = $sql;
        return $this->db->query($sql);
    }

    public function exec($sql)
    {
        $this->logs[] = $sql;
        return $this->db->exec($sql);
    }

}