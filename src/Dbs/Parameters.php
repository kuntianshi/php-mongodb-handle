<?php
/**
 * 获取数据库级别信息
 * Created by PhpStorm.
 * User: shikun
 * Date: 2017/11/19
 * Time: 16:28
 */

namespace ShiKung\Mongodb\Dbs;

use MongoDB\Driver\ReadPreference;

class Parameters
{

    public static function preferences()
    {
        return [
            ReadPreference::RP_PRIMARY,
            ReadPreference::RP_NEAREST,
            ReadPreference::RP_PRIMARY_PREFERRED,
            ReadPreference::RP_SECONDARY,
            ReadPreference::RP_SECONDARY_PREFERRED
        ];
    }

}