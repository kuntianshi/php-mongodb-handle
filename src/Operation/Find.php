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
use MongoDB\Driver\Command;
use MongoDB\Driver\Exception\UnexpectedValueException;
use ShiKung\Mongodb\Dbs\Constant;

class Find
{
    use Constant;

    /**
     * @param Server $server
     * @param $db
     * @param $collection
     * @param array $condition
     * @param array $fields
     * @param array $options
     * @param array $map
     * @return false|array
     */
    public function findRow($server, $db, $collection, $condition = [], $fields = [], $options = [], $map = [])
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
        $cursor->setTypeMap($map ? $map : $this->map);
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
     * @param $options
     * @param $map
     * @return mixed
     */
    public function findAll($server, $db, $collection, $condition = [], $skip = 0, $limit = 0, $sortFields = [], $fields = [], $options = [], $map = [])
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
        $result->setTypeMap($map ? $map : $this->map);
        return $result->toArray();
    }

    public function findAndModify()
    {
        //undo
    }

    /**
     * @param Server $server
     * @param $db
     * @param $collection
     * @param $condition
     * @return int
     */
    public function count($server, $db, $collection, $condition)
    {
        $cmd = ['count' => $collection];
        if (!empty($condition)) {
            $cmd['query'] = $condition;
        }
        $readPreference = null;
        $command = new Command($cmd);
        $cursor = $server->executeCommand($db, $command, $readPreference);
        $result = current($cursor->toArray());
        if (!isset($result->n) || !(is_integer($result->n) || is_float($result->n))) {
            throw new UnexpectedValueException('count command did not return a numeric "n" value');
        }
        return (integer)$result->n;
    }
}