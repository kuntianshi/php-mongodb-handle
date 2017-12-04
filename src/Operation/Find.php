<?php
/**
 * Created by PhpStorm.
 * User: shikun
 * Date: 2017/11/19
 * Time: 16:28
 */

namespace ShiKung\Mongodb\Operation;

use MongoDB\Driver\ReadPreference;
use MongoDB\Driver\Query;
use MongoDB\Driver\Server;

class Find
{

    /**
     * @param Server $server
     * @param $db
     * @param $collection
     * @param array $condition
     * @param array $fields
     * @param array $options
     * @return false|array
     */
    public function findRow($server, $db, $collection, $condition = [], $fields = [], $options = [])
    {
        if (!isset($fields['_id'])) {
            $fields['_id'] = 0;
        }
        $options = array(
            'projection' => $fields,
            'limit' => 1
        );
        $readPreference = new ReadPreference(ReadPreference::RP_PRIMARY);
        $query = new Query($condition, $options);
        $cursor = $server->executeQuery($db . "." . $collection, $query, $readPreference);
        $cursor->setTypeMap(array('root' => 'array', 'document' => 'array', 'array' => 'array'));
        $arr = $cursor->toArray();
        if ($arr && count($arr) > 0) {
            return $arr[0];
        } else {
            return false;
        }
    }

    /**
     * @param Server $server
     * @param String $db
     * @param $collection
     * @param array $condition
     * @param int $skip
     * @param int $limit
     * @param array $sortFields
     * @param array $fields
     * @return mixed
     */
    public function findAll($server, $db, $collection, $condition = [], $skip = 0, $limit = 0, $sortFields = [], $fields = [], $options = [])
    {
        $skip = intval($skip);
        $limit = intval($limit);
        if (!isset($fields['_id'])) {
            $fields['_id'] = 0;
        }
        $options = array_merge(
            [
                'projection' => $fields,
                'limit' => $limit,
                'skip' => $skip,
                'sort' => $sortFields
            ], $options);
        $query = new Query($condition, $options);
        $result = $server->executeQuery($db . '.' . $collection, $query);
        $result->setTypeMap(array('root' => 'array', 'document' => 'array', 'array' => 'array'));
        return $result->toArray();
    }

    public function findAndModify()
    {
        //undo
    }
}