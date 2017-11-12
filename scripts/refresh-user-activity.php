<?php

use Phalcon\Di;
use Phalcon\Logger\Adapter\Stream;
use Phosphorum\Models\UsersActivities;
use Phosphorum\Models\Bank;

require 'cli-bootstrap.php';

function refresh_data() {

    $log = new Stream('php://stdout');
    $log->info('Start');

    $yesterday = time() - (24 * 60 * 60);

    $database = Di::getDefault()->getShared('db');

    $database->query("DELETE FROM users_activities WHERE created_at < $yesterday");

    $parameters = [
        "limit" => 10,
        "order" => "page_views DESC"
    ];

    $todayList = UsersActivities::find($parameters);

    if (count($todayList) >= 10) {
        foreach ($todayList as $userActivity) {
            echo $userActivity->user->name . '\n';
            Bank::handleDailyIncome($userActivity->user);
        }
    }
}

refresh_data();
