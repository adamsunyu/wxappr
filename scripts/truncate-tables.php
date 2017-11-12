<?php

use Phalcon\Di;
use Phalcon\Logger\Adapter\Stream;

require 'cli-bootstrap.php';

function truncate_tables($table_list) {

    $log = new Stream('php://stdout');
    $log->info('Start');

    $database = Di::getDefault()->getShared('db');

    $database->query("SET FOREIGN_KEY_CHECKS = 0");

    foreach($table_list as $table_name) {
        $log->info('Truncate table: ' . $table_name);

        try {
            $database->query("TRUNCATE TABLE $table_name");
        } catch(Exception $e) {
            $log->info($e->getMessage() . PHP_EOL);
        }
    }

    $database->query("SET FOREIGN_KEY_CHECKS = 1");
}

function change_table_charset($table_list) {
    $log = new Stream('php://stdout');
    $log->info('Start');

    $database = Di::getDefault()->getShared('db');

    foreach($table_list as $table_name) {
        $log->info('Change table charset :' . $table_name);

        try {
            $database->query("ALTER TABLE $table_name CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        } catch(Exception $e) {
            $log->info($e->getMessage() . PHP_EOL);
        }
    }
}

function optimize_table($table_list) {
    $log = new Stream('php://stdout');
    $log->info('Start');

    $database = Di::getDefault()->getShared('db');

    foreach($table_list as $table_name) {
        $log->info('Optimize table :' . $table_name);

        try {
            $database->query("optimize TABLE $table_name");
        } catch(Exception $e) {
            $log->info($e->getMessage() . PHP_EOL);
        }
    }
}

$table_list = [
    'activities',
    'activity_notifications',
    'changes_login',
    'changes_nickname',
    'changes_password',
    'cities',
    'email_confirmations',
    'images',
    'logins_failed',
    'logins_success',
    'messages_inbox',
    'messages_outbox',
    'nodes',
    'notifications',
    'notifications_bounces',
    'permissions',
    'posts',
    'posts_activities',
    'posts_history',
    'posts_notifications',
    'posts_replies',
    'posts_replies_history',
    'posts_replies_votes',
    'posts_subscribers',
    'posts_thanks',
    'posts_views',
    'posts_votes',
    'remember_tokens',
    'reset_passwords',
    'roles',
    'topic_tracking',
    'users',
    'users_activities',
    'users_badges',
    'users_bankbook',
    'users_codes',
    'users_followers',
    'users_nodes',
    'users_social'
];

//truncate_tables($table_list);
//change_table_charset($table_list);
//optimize_table($table_list);
