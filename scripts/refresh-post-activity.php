<?php

use Phalcon\Di;
use Phalcon\Logger\Adapter\Stream;

require 'cli-bootstrap.php';

function refresh_data() {

    $log = new Stream('php://stdout');
    $log->info('Start');

    $yesterday = time() - (72 * 60 * 60);

    $database = Di::getDefault()->getShared('db');

    $database->query("DELETE FROM posts_activities WHERE created_at < $yesterday");
}

refresh_data();
