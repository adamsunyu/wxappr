<?php

/**
 * This script sends a weekly digest to users
 */
require 'cli-bootstrap.php';

use Phosphorum\Mail\Digest;

try {
    $digest = new Digest();
    $digest->send();
} catch (Exception $e) {
    echo $e->getMessage(), PHP_EOL;
    echo $e->getTraceAsString();
}
