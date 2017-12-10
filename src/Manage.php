<?php
/**
 * Created by PhpStorm.
 * User: shikun
 * Date: 2017/11/11
 * Time: 17:53
 */

namespace ShiKung\Mongodb;

use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\Timestamp;

use ShiKung\Mongodb\Dbs\Basic;
use ShiKung\Mongodb\Operation\Delete;
use ShiKung\Mongodb\Operation\Find;
use ShiKung\Mongodb\Operation\Insert;
use ShiKung\Mongodb\Operation\Update;


class Manage
{
    /**
     * @var
     */
    private $db;
    /**
     * @var
     */
    private $server;

    /**
     * Manage constructor.
     * @param string $uri
     * @param string $db
     */
    public function __construct($uri = '', $db = '')
    {
        $connection = new Connection($uri, $db);
        $this->server = $connection->getServer();
        $this->db = $db;
    }

    /**
     * @param $collection
     * @param $condition
     * @param array $field
     * @param array $options
     * @return array|false
     */
    public function findRow($collection, $condition, $field = [], $options = [])
    {
        $fetch = new Find();
        return $fetch->findRow($this->server, $this->db, $collection, $condition, $field, $options);
    }

    /**
     * @param $collection
     * @param array $condition
     * @param int $skip
     * @param int $limit
     * @param array $sortFields
     * @param array $fields
     * @param array $options
     * @return mixed
     */
    public function findAll($collection, $condition = [], $skip = 0, $limit = 0, $sortFields = [], $fields = [], $options = [])
    {
        $find = new Find();
        return $find->findAll($this->server, $this->db, $collection, $condition, $skip, $limit, $sortFields, $fields, $options);
    }

    /**
     * @param $collection
     * @param $bind
     * @param int $timeout
     * @return bool|int
     */
    public function insert($collection, $bind, $timeout = 1000)
    {
        $insert = new Insert();
        return $insert->insert($this->server, $this->db, $collection, $bind, $timeout);
    }

    /**
     * @param $collection
     * @param $bind
     * @param int $timeout
     * @return bool
     */
    public function batchInsert($collection, $bind, $timeout = 1000)
    {
        $insert = new Insert();
        return $insert->batchInsert($this->server, $this->db, $collection, $bind, $timeout);
    }

    /**
     * @param $collection
     * @param $condition
     * @param $new_data
     * @param array $options
     * @return bool
     */
    public function update($collection, $condition, $new_data, $options = [])
    {
        $update = new Update();
        return $update->update($this->server, $this->db, $collection, $condition, $new_data, $options);
    }

    /**
     * @param $collection
     * @param $condition
     * @param $new_data
     * @return mixed
     */
    public function batchUpdate($collection, $condition, $new_data)
    {
        $update = new Update();
        return $update->batchUpdate($this->server, $this->db, $collection, $condition, $new_data);
    }

    /**
     * @param $collection
     * @param $condition
     * @return int
     */
    public function count($collection, $condition)
    {
        $find = new Find();
        return $find->count($this->server, $this->db, $collection, $condition);
    }

    /**
     * @param $collection
     * @param $condition
     * @param array $options
     * @return bool|int
     */
    public function delete($collection, $condition, $options = [])
    {
        $delete = new Delete();
        return $delete->delete($this->server, $this->db, $collection, $condition, $options);
    }

    /**
     * @param $timestamp
     * @return mixed
     */
    public static function getMongoDate($timestamp = 0)
    {
        return (!$timestamp) ? new UTCDateTime(time() * 1000) : new UTCDateTime($timestamp * 1000);
    }

    /**
     * @param Timestamp $Date
     * @param $format
     * @return mixed
     */
    public static function getDate($Date, $format = 'Y-m-d')
    {
        return date($format, ((string)$Date) / 1000);
    }

    /**
     * @return array
     */
    public function getServerInfo()
    {
        $server = new Basic();
        return $server->getServerInfo($this->server);
    }
}