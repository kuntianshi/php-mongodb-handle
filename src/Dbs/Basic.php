<?php
/**
 * 获取数据库级别信息
 * Created by PhpStorm.
 * User: shikun
 * Date: 2017/11/19
 * Time: 16:28
 */

namespace ShiKung\Mongodb\Dbs;


use MongoDB\Driver\Server;

class Basic
{

    /**
     * @param $server
     * @return array
     */
    public function getServerInfo($server)
    {
        $data = [
            'host' => $this->getHost($server),
            'port' => $this->getPort($server),
            'latency' => $this->getLatency($server),
            'tags' => $this->getTags($server),
            'type' => $this->getType($server),
            'is_arbiter' => $this->isArbiter($server),
            'is_hidden' => $this->isHidden($server),
            'is_passive' => $this->isPassive($server),
            'is_primary' => $this->isPrimary($server),
            'is_secondary' => $this->isSecondary($server)
        ];
        $info = $this->getInfo($server);
        return array_merge($data, $info);
    }

    /**
     * @param Server $server
     * @return mixed
     */
    private function getHost($server)
    {
        return $server->getHost();
    }

    /**
     * @param Server $server
     * @return mixed
     */
    private function getInfo($server)
    {
        return $server->getInfo();
    }

    /**
     * @param Server $server
     * @return mixed
     */
    private function getLatency($server)
    {
        return $server->getLatency();
    }

    /**
     * @param Server $server
     * @return mixed
     */
    private function getPort($server)
    {
        return $server->getPort();
    }

    /**
     * @param Server $server
     * @return mixed
     */
    private function getTags($server)
    {
        return $server->getTags();
    }

    /**
     * @param Server $server
     * @return mixed
     */
    private function getType($server)
    {
        return $server->getType();
    }

    /**
     * @param Server $server
     * @return mixed
     */
    private function isArbiter($server)
    {
        return $server->isArbiter();
    }

    /**
     * @param Server $server
     * @return mixed
     */
    private function isHidden($server)
    {
        return $server->isHidden();
    }

    /**
     * @param Server $server
     * @return mixed
     */
    private function isPassive($server)
    {
        return $server->isPassive();
    }

    /**
     * @param Server $server
     * @return mixed
     */
    private function isPrimary($server)
    {
        return $server->isPrimary();
    }

    /**
     * @param Server $server
     * @return mixed
     */
    private function isSecondary($server)
    {
        return $server->isSecondary();
    }
}