<?php
/**
 * Created by PhpStorm.
 * User: shikun
 * Date: 2017/11/19
 * Time: 16:42
 */

namespace ShiKung\Mongodb;

use MongoDB\Driver\Manager;
use Exception;
use MongoDB\Driver\Exception\InvalidArgumentException;
use MongoDB\Driver\Exception\RuntimeException;
use MongoDB\Driver\Server;
use MongoDB\Driver\ReadPreference;
use MongoDB\Driver\Exception\ConnectionException;
use MongoDB\Driver\Exception\AuthenticationException;

class Connection
{
    /**
     * @var string
     */
    private $uri;
    /**
     * MongoDB\Driver\Manager
     * @var
     */
    private $manager;

    /**
     * @var string
     */
    private $db;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var array
     */
    private $driverOptions = [];

    /**
     * @var
     */
    private $error;

    /**
     * @var int
     */
    private $retry = 3;

    /**
     * Connection constructor.
     * @param string $uri
     * @param string $db
     * @throws Exception
     */
    public function __construct($uri = '', $db = '')
    {
        if (!$uri) {
            $this->uri = env('MONGO_DB_URI');
        } else {
            $this->uri = $uri;
        }

        if (!$db) {
            $this->db = env('MONGO_DB');
        } else {
            $this->db = $db;
        }
        if (!$this->uri) {
            throw new Exception('请配置数据库连接');
        }
        if (!$this->db) {
            throw new Exception('请配置数据库');
        }
    }

    /**
     * @param array $options
     * @param array $driverOptions
     * @return Manager
     * @throws Exception
     */
    private function getManager($options = [], $driverOptions = [])
    {
        $this->options = array_merge($options, $this->options);
        $this->driverOptions = array_merge($driverOptions, $this->driverOptions);
        try {
            return new Manager($this->uri, $this->options, $this->driverOptions);
        } catch (InvalidArgumentException $exception) {
            $this->error = $exception->getMessage();
        } catch (RuntimeException $exception) {
            $this->error = $exception->getMessage();
        }
        throw new Exception("Mongodb Manager is error,the error is:" . $this->error);
    }

    /**
     * @param int $readPreference
     * @return Server
     * @throws Exception
     */
    public function getServer($readPreference = ReadPreference::RP_PRIMARY)
    {
        try {
            return $this->getManager()->selectServer(new ReadPreference($readPreference));
        } catch (ConnectionException $ex) {
            $this->error = $ex->getMessage();
        } catch (AuthenticationException $ex) {
            $this->error = $ex->getMessage();
        }
        if ($this->retry > 0) {
            $this->getServer(--$this->retry);
        } else {
            throw new Exception('DB connection timeout');
        }
    }
}