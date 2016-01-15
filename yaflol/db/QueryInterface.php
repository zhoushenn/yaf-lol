<?php
/**
 * @author: zhouwenlong
 * @since: 2016/1/15
 */

namespace yol\db;


interface QueryInterface
{
    public function sqlTrace();

    public function query($sql);

    public function exec($sql);
}