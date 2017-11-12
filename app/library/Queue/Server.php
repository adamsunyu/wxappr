<?php

/*
 +------------------------------------------------------------------------+
 | Phosphorum                                                             |
 +------------------------------------------------------------------------+
 | Copyright (c) 2013-2016 Phalcon Team and contributors                  |
 +------------------------------------------------------------------------+
 | This source file is subject to the New BSD License that is bundled     |
 | with this package in the file docs/LICENSE.txt.                        |
 |                                                                        |
 | If you did not receive a copy of the license and are unable to         |
 | obtain it through the world-wide-web, please send an email             |
 | to admin@wxappr.com so we can send you a copy immediately.             |
 +------------------------------------------------------------------------+
*/

namespace Phosphorum\Queue;

/**
 * Server
 *
 * Facade to Phalcon\Queue\Beanstalkd
 */
class Server
{
    /**
     * Server constructor
     *
     * @param \Phalcon\Queue\Beanstalk $queue
     */
    public function __construct($queue)
    {
        $this->queue = $queue;
    }

    /**
     * Simulates putting a job in the queue
     *
     * @param array $job
     * @return bool
     */
    public function put($job)
    {
        return true;
    }
}
