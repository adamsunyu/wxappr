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

use Phalcon\Error\Error;

/**
 * Error Controller
 *
 * @package Phosphorum\Controllers
 */
class ErrorController extends ControllerBase
{
    /**
     * @var \Phalcon\Error\Error
     */
    protected $error;

    public function initialize()
    {
        /** @var \Phalcon\Error\Error $error */
        $error = $this->dispatcher->getParam('error');

        if (!$error instanceof Error) {
            $error = new Error([
                'type'        => -1,
                'message'     => 'Something is not quite right',
                'file'        => __FILE__,
                'line'        => __LINE__,
                'exception'   => null,
                'isException' => false,
                'isError'     => true,
            ]);
        }

        $this->error = $error;

        $this->view->setVars([
            'error' => $this->error,
            'debug' => (ENV_DEVELOPMENT === APPLICATION_ENV || ENV_TESTING === APPLICATION_ENV),
        ]);
    }

    public function indexAction()
    {
        switch ($this->error->type()) {
            case 404:
                $this->tag->setTitle('Page not found');
                $code = 404;
                $message = 'Unfortunately, the page you are requesting can not be found!';
                break;
            case 403:
                $this->tag->setTitle('Access is denied');
                $code = 403;
                $message = 'Access to this resource is denied by the administrator.';
                break;
            case 401:
                $this->tag->setTitle('Authorization required');
                $code = 401;
                $message = 'To access the requested resource requires authentication.';
                break;
            default:
                $this->tag->setTitle('Something is not quite right');
                $code = 500;
                $message = 'Unfortunately an unexpected system error occurred.';
        }

        $this->response->resetHeaders()->setStatusCode($code, null);

        $this->view->setVars([
            'code'    => $code,
            'message' => $message,
        ]);
    }

    public function route404Action()
    {
        $this->tag->setTitle('Page not found');
        $code = 404;

        $this->view->setVars([
            'code'    => $code,
            'message' => 'Unfortunately, the page you are requesting can not be found!',
        ]);

        $this->response->resetHeaders()->setStatusCode($code, 'Not Found');
    }

    public function route503Action()
    {
        $this->tag->setTitle('系统维护通知');
        $code = 503;

        $this->view->setVars([
            'code'    => $code,
            'message' => '我们在进行系统维护，请稍等片刻!',
        ]);

        $this->response->resetHeaders()->setStatusCode($code, 'Service Unavailable');
    }
}
