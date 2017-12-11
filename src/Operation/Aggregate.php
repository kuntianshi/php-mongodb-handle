<?php
/**
 * Created by PhpStorm.
 * User: shikun
 * Date: 2017/11/19
 * Time: 16:28
 */

namespace ShiKung\Mongodb\Operation;

use MongoDB\Driver\Exception\InvalidArgumentException;
use MongoDB\Driver\ReadPreference;
use MongoDB\Driver\Server;
use MongoDB\Driver\Command;
use stdClass;
use ShiKung\Mongodb\Dbs\Parameters;

class Aggregate
{

    private $error;

    /**
     * @param Server $server
     * @param $db
     * @param $collection
     * @param $pipeline
     * @param $options
     * @param $readPreference
     * @return bool|array
     */
    public function aggregate($server, $db, $collection, $pipeline = [], $options = [], $readPreference = ReadPreference::RP_PRIMARY)
    {
        if (empty($pipeline)) {
            throw new InvalidArgumentException('pipeline is empty');
        }
        $cmd = [
            'aggregate' => $collection,
            'pipeline' => $pipeline,
        ];
        if (isset($options['useCursor']) && $options['useCursor']) {
            $cmd['cursor'] = isset($options["batchSize"])
                ? ['batchSize' => $options["batchSize"]]
                : new stdClass;
        } else {
            $cmd['cursor'] = new stdClass;
        }
        if (!in_array($readPreference, Parameters::preferences())) {
            throw new InvalidArgumentException('readPreference is error');
        }

        $command = new Command($cmd);
        try {
            $result = $server->executeCommand($db, $command, $readPreference);
            $result->setTypeMap(array('root' => 'array', 'document' => 'array', 'array' => 'array'));
            return $result->toArray();
        } catch (InvalidArgumentException $e) {
            $this->error = $e->getMessage();
            throw new InvalidArgumentException($e->getMessage());
        }
    }
}