<?php

use Phalcon\Di;
use Phalcon\Logger\Adapter\Stream;
use Phosphorum\Models\Posts;

require 'cli-bootstrap.php';

function refresh_data() {

    $log = new Stream('php://stdout');
    $log->info('Start');

    $database = Di::getDefault()->getShared('db');

    $now = time();

    $database->query("update posts SET sticked = 'N' WHERE sticked_endtime < $now AND sticked = 'Y' ");
}

refresh_data();
