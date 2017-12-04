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

use ShiKung\Mongodb\Operation\Find;

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
}