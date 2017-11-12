<?php

/*
 +------------------------------------------------------------------------+
 | wxappr.com                                                             |
 +------------------------------------------------------------------------+
 | Copyright (c) 2016-2017 Simon Fan and contributors                     |
 +------------------------------------------------------------------------+
 | This source file is subject to the New BSD License that is bundled     |
 | with this package in the file docs/LICENSE.txt.                        |
 |                                                                        |
 | If you did not receive a copy of the license and are unable to         |
 | obtain it through the world-wide-web, please send an email             |
 | to admin@wxappr.com so we can send you a copy immediately.             |
 +------------------------------------------------------------------------+
*/

use Phalcon\Config;

return new Config([
    'application' => [
        'debug' => true,
    ],
    'volt' => [
        'forceCompile' => true,
    ],
    'metadata' => [
        'adapter' => 'Memory',
    ],
    'dataCache' => [
        'backend'  => 'Memory',
        'frontend' => 'None',
    ],
    'modelsCache' => [
        'backend'  => 'Memory',
        'frontend' => 'None',
    ],
    'viewCache' => [
        'backend' => 'Memory',
    ],
]);
