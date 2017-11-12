<?php

/*
 +------------------------------------------------------------------------+
 | wxappr.com                                                             |
 +------------------------------------------------------------------------+
 | Copyright (c) 2013-2016                                                |
 +------------------------------------------------------------------------+
 | This source file is subject to the New BSD License that is bundled     |
 | with this package in the file docs/LICENSE.txt.                        |
 |                                                                        |
 | If you did not receive a copy of the license and are unable to         |
 | obtain it through the world-wide-web, please send an email             |
 | to admin@wxappr.com so we can send you a copy immediately.             |
 +------------------------------------------------------------------------+
*/

use Phosphorum\Bootstrap;

include_once realpath(dirname(dirname(__FILE__))) . '/app/config/env.php';
include_once BASE_DIR . 'app/library/Bootstrap.php';

$bootstrap = new Bootstrap();

if (APPLICATION_ENV == ENV_TESTING) {
    return $bootstrap->run();
} else {
    echo $bootstrap->run();
}
