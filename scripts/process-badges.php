<?php

/**
 * This script processes badges for users
 */
require 'cli-bootstrap.php';

use Phosphorum\Badges\Manager as BadgesManager;
use Phalcon\DI\Injectable;

class ProcessBadges extends Injectable
{

    public function run()
    {
        $manager = new BadgesManager;
        $manager->process();
    }
}

try {
    $task = new ProcessBadges($config);
    $task->run();
} catch (Exception $e) {
    echo $e->getMessage(), PHP_EOL;
    echo $e->getTraceAsString();
}
