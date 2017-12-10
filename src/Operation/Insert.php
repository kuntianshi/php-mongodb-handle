<?php
/**
 * Created by PhpStorm.
 * User: shikun
 * Date: 2017/11/19
 * Time: 18:34
 */

namespace ShiKung\Mongodb\Operation;


use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Exception\BulkWriteException;
use MongoDB\Driver\Exception\InvalidArgumentException;
use MongoDB\Driver\Server;
use MongoCursorException;
use MongoDB\Driver\WriteConcern;

class Insert
{

    private $error;

    /**
     * @param Server $server
     * @param $db
     * @param $collection
     * @param $bind
     * @param $timeout
     * @return int|bool
     */
    public function insert($server, $db, $collection, $bind, $timeout = 1000)
    {
        if (!is_string($collection) || (trim($collection)) == '') {
            throw new InvalidArgumentException('Invalid collection ' . $collection . ' the collection is must input');
        }
        if (!(count($bind) > 0)) {
            throw new InvalidArgumentException('Invalid collection  the data is must input');
        }
        $bulk = new BulkWrite();
        $bind['_id'] = $bulk->insert($bind);
        try {
            $writeConcern = new WriteConcern(WriteConcern::MAJORITY, $timeout);
            $result = $server->executeBulkWrite($db . '.' . $collection, $bulk, $writeConcern);
            return $result->getInsertedCount();
        } catch (BulkWriteException $ex) {
            $this->error = $ex->getMessage();
            return false;
        }
    }

    /**
     * @param Server $server
     * @param $db
     * @param $collection
     * @param $bind
     * @param $timeout
     * @return bool
     */
    public function batchInsert($server, $db, $collection, $bind, $timeout = 1000)
    {
        if (!is_string($collection) || (trim($collection)) == '') {
            throw new InvalidArgumentException('Invalid collection ' . $collection . ' the collection is must input');
        }
        if (!(count($bind) > 0)) {
            throw new InvalidArgumentException('Invalid collection  the data is must input');
        }
        $bulk = new BulkWrite();
        foreach ($bind as $key => $value) {
            $bulk->insert($value);
        }
        try {
            $writeConcern = new WriteConcern(WriteConcern::MAJORITY, $timeout);
            $result = $server->executeBulkWrite($db . '.' . $collection, $bulk, $writeConcern);
            return $result->getInsertedCount();
        } catch (MongoCursorException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }
}