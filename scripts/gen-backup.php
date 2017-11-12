<?php

/**
 * This script generates backup and uploads it to Dropbox
 */
require 'cli-bootstrap.php';

use Phosphorum\Utils\Backup;
use Phalcon\DI\Injectable;

class GenerateBackup extends Injectable
{
    public function run()
    {
        $backup = new Backup;
        $backup->generate();
    }
}

try {
    $task = new GenerateBackup;
    $task->run();
} catch (Exception $e) {
    echo $e->getMessage(), PHP_EOL;
    echo $e->getTraceAsString();
}
