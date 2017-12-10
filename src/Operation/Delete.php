<?php
/**
 * Created by PhpStorm.
 * User: shikun
 * Date: 2017/11/19
 * Time: 16:28
 */

namespace ShiKung\Mongodb\Operation;

use MongoDB\Driver\BulkWrite;
use MongoDB\Driver\Exception\InvalidArgumentException;
use MongoDB\Driver\Server;
use MongoDB\Driver\WriteConcern;

class Delete
{

    private $error;

    /**
     * @param Server $server
     * @param $db
     * @param $collection
     * @param $condition
     * @param $options
     * @param $timeout
     * @return bool|int
     */
    public function delete($server, $db, $collection, $condition, $options, $timeout = 1000)
    {
        $bulk = new BulkWrite();
        $bulk->delete($condition, $options);
        $writeConcern = new WriteConcern(WriteConcern::MAJORITY, $timeout);
        try {

            $cursor = $server->executeBulkWrite($db . '.' . $collection, $bulk, $writeConcern);
            return $cursor->getDeletedCount();
        } catch (InvalidArgumentException $exception) {
            $this->error = $exception->getMessage();
        }
        return false;
    }
}