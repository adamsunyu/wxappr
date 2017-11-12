<?php

/**
 * Index all existing documents to elastic search
 */
require 'cli-bootstrap.php';

use Phosphorum\Search\Indexer;
use Phalcon\DI\Injectable;

class SearchTasks extends Injectable
{
    public function run()
    {
        $search = new Indexer();
        $search->indexAll();
    }
}

try {
    $task = new SearchTasks;
    $task->run();
} catch (Exception $e) {
    echo $e->getMessage(), PHP_EOL;
    echo $e->getTraceAsString();
}
