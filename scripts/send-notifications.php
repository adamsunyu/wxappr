<?php

/**
 * This scripts generates random posts
 */
require 'cli-bootstrap.php';

use Phosphorum\Mail\SendSpool;

class SendSpoolTask extends Phalcon\DI\Injectable
{

    public function run()
    {
        $spool = new SendSpool();
        $spool->sendRemaining();
    }
}

try {
    $task = new SendSpoolTask($config);
    $task->run();
} catch (Exception $e) {
    echo $e->getMessage(), PHP_EOL;
    echo $e->getTraceAsString();
}
