<?php

use Phalcon\Di;
use Phalcon\Logger\Adapter\Stream;
use Phosphorum\Models\Posts;

require 'cli-bootstrap.php';

function migrate_v125() {

    $log = new Stream('php://stdout');
    $log->info('Start');

    $yesterday = time() - (24 * 60 * 60);

    $database = Di::getDefault()->getShared('db');

    // const NEW_POST = 'NP' P => NP
    // const NEW_REPLY = 'NR' C => NR
    // const VOTE_UP_POST = 'VP' O => VP
    // const VOTE_UP_REPLY = 'VR' R => VR

    // const NEW_POST = 'P';
    // const NEW_REPLY = 'C';
    // const VOTE_UP_POST = 'O';
    // const VOTE_UP_REPLY = 'R';

    $database->query("UPDATE activities SET type = 'NP' WHERE type = 'P'");
    $database->query("UPDATE activities SET type = 'NR' WHERE type = 'C'");
    $database->query("UPDATE activities SET type = 'VP' WHERE type = 'O'");
    $database->query("UPDATE activities SET type = 'VR' WHERE type = 'R'");
    $database->query("UPDATE activities SET type = 'NU' WHERE type = 'U'");

    /**
     * 'U' : 注册
     * 'S' : 置顶话题
     * 'P' : 赞主题
     * 'C' : 回复
     * 'R' : 赞回复
     */

    //  const NEW_USER = 'NU';
    //  const STICK_POST = 'SP';
    //  const NEW_REPLY = 'NR';
    //  const VOTE_UP_POST = 'VP';
    //  const VOTE_UP_REPLY = 'VR';

    //  const NEW_ANSWER = 'NA';
    //  const VOTE_UP_ANSWER = 'VA';

    $database->query("UPDATE activity_notifications SET type = 'NU' WHERE type = 'U'");
    $database->query("UPDATE activity_notifications SET type = 'SP' WHERE type = 'S'");
    $database->query("UPDATE activity_notifications SET type = 'VP' WHERE type = 'P'");
    $database->query("UPDATE activity_notifications SET type = 'NR' WHERE type = 'C'");
    $database->query("UPDATE activity_notifications SET type = 'VR' WHERE type = 'R'");
    $database->query("UPDATE activity_notifications SET type = 'DI' WHERE type = 'D'");

    $log->info('End');
}

migrate_v126();
