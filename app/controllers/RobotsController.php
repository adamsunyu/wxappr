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

namespace Phosphorum\Controllers;

use DateTime;
use DateTimeZone;
use Phalcon\Http\Response;

/**
 * Class RobotsController
 *
 * We have rewrite rule for Nginx
 * robots.txt => robots
 *
 * @package Phosphorum\Controllers
 */
class RobotsController extends ControllerBase
{
    public function initialize()
    {
        $this->view->disable();
    }

    /**
     * Generate the website robots.txt
     */
    public function indexAction()
    {
        $response = new Response();

        $expireDate = new DateTime('now', new DateTimeZone('UTC'));
        $expireDate->modify('+1 month');
        $response->setExpires($expireDate);

        $response->setHeader('Content-Type', "text/plain; charset=UTF-8");

        $baseUrl = rtrim($this->config->get('site')->url, '/');
        $content=<<<EOL
User-agent: *
Allow: /
Sitemap: $baseUrl/sitemap.xml
EOL;
        $response->setContent($content);

        return $response;
    }
}
