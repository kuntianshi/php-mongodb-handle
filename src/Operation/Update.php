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

class Update
{

    private $error;

    /**
     * @param Server $server
     * @param $db
     * @param $collection
     * @param $condition
     * @param $new_data
     * @param array $options
     * @param int $timeout
     * @return bool
     */
    public function update($server, $db, $collection, $condition, $new_data, $options = ['multi' => true], $timeout = 1000)
    {
        if (!is_string($collection) || (trim($condition) == '')) {
            throw new InvalidArgumentException('Invalid collection name');
        }
        if (!is_array($condition)) {
            throw new InvalidArgumentException('Invalid condition data. The condition data should be a array');
        }
        try {
            $bulk = new BulkWrite();
            $bulk->update($condition, $new_data, $options);
            $writeConcern = new WriteConcern(WriteConcern::MAJORITY, $timeout);
            $cursor = $server->executeBulkWrite($db . '.' . $collection, $bulk, $writeConcern);
            return $cursor->getModifiedCount();
        } catch (MongoCursorException $exception) {
            $this->error = $exception->getMessage();
            return false;
        }
    }

    /**
     * @param Server $server
     * @param $db
     * @param $collection
     * @param $new_data
     * @param int $timeout
     * @return bool
     */
    public function batchUpdate($server, $db, $collection, $new_data, $timeout = 1000)
    {
        if (!is_string($collection) && (trim($collection) == '')) {
            throw new InvalidArgumentException('Invalid collection name.');
        }
        if (!is_array($new_data)) {
            throw new InvalidArgumentException('Invalid condition data. The condition data should be a array');
        }
        try {
            $bulk = new BulkWrite();
            foreach ($new_data as $key1 => $value1) {
                $bulk->update($value1['condition'], $value1['new_data'], isset($value1['option']) ? $value1['option'] : array());
            }
            $writeConcern = new WriteConcern(WriteConcern::MAJORITY, $timeout);
            $result = $server->executeBulkWrite($db . '.' . $collection, $bulk, $writeConcern);
            return $result->getUpsertedCount() + $result->getMatchedCount();
        } catch (MongoCursorException $exception) {
            $this->error = $exception->getMessage();
            return false;
        } catch (BulkWriteException $exception) {
            $this->error = $exception->getMessage();
            return false;
        }
    }
}