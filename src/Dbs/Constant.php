<?php

/**
 * Created by PhpStorm.
 * User: a88wa
 * Date: 2018/7/16
 * Time: 11:47
 */

namespace ShiKung\Mongodb\Dbs;

trait Constant
{
    private $db_default_timeout = 1000;
    private $map = ['root' => 'array', 'document' => 'array', 'array' => 'array'];
}