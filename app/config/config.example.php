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
use Phalcon\Logger;

if (!defined('BASE_DIR')) {
    require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'env.php';
}

return new Config([
    'site' => [
        'name'        => 'wxappr',
        'url'         => 'https://www.wxappr.com',
        'description' => 'A forum',
        'keywords'    => 'programmer',
        'project'     => 'ChangX',
        'software'    => 'wxappr',
        'repo'        => '',
        'docs'        => '',
    ],

    'database' => [
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'homestead',
        'password' => 'secret',
        'dbname'   => 'wxappr',
        'charset'  => 'utf8'
    ],

    'metadata' => [
        'adapter'     => 'Files',
        'metaDataDir' => BASE_DIR . 'app/cache/metaData/',
    ],

    'application' => [
        'controllersDir' => BASE_DIR . 'app/controllers/',
        'modelsDir'      => BASE_DIR . 'app/models/',
        'viewsDir'       => BASE_DIR . 'app/views/',
        'formsDir'       => BASE_DIR . 'app/forms/',
        'pluginsDir'     => BASE_DIR . 'app/plugins/',
        'libraryDir'     => BASE_DIR . 'app/library/',
        'cryptSalt'      => 'eEAfR|_&G&f,+vU]:jFan!A&+71w1Ms9~8_4L!<@[N@DyaIP_2My|:+.u>/6m,$D',
        'development'    => [
            'staticBaseUri' => '/',
            'baseUri'       => '/'
        ],
        'production' => [
            'staticBaseUri' => '/',
            'baseUri'       => '/'
        ],
        'debug' => true
    ],

    'volt' => [
        'compiledExt'  => '.php',
        'separator'    => '_',
        'cacheDir'     => BASE_DIR . 'app/cache/volt/',
        'forceCompile' => true,
    ],

    'dataCache' => [
        'backend'  => 'File',
        'frontend' => 'Data',
        'lifetime' => 30 * 24 * 60 * 60,
        'prefix'   => 'forum-data-cache-',
        'cacheDir' => BASE_DIR . 'app/cache/data/',
    ],

    'modelsCache' => [
        'backend'  => 'File',
        'frontend' => 'Data',
        'lifetime' => 24 * 60 * 60,
        'prefix'   => 'forum-models-cache-',
        'cacheDir' => BASE_DIR . 'app/cache/models/',
    ],

    'viewCache' => [
        'backend'  => 'File',
        'lifetime' => 30 * 24 * 60 * 60,
        'prefix'   => 'forum-views-cache-',
        'cacheDir' => BASE_DIR . 'app/cache/views/',
    ],

    'session' => [
        'adapter' => 'Files',
    ],

    'mandrillapp' => [
        'secret' => ''
    ],

    'github' => [
        'clientId'     => 'dbb1f5e8a60652c2237e',
        'clientSecret' => '4223b71d9341b8fcf9a4eb51028ca7a6107c75c8',
        'redirectUri'  => 'https://www.wxappr.com/login/oauth/access_token/'
    ],

    // Visit https://www.dropbox.com/developers/apps and get your "accessToken" and "appSecret".
    'dropbox' => [
        'accessToken' => '',
        'appSecret' => '',
        'prefix' => '',
    ],

    'amazonSns' => [
        'secret' => ''
    ],

    'beanstalk' => [
        'disabled' => true,
        'host'     => '127.0.0.1'
    ],

    'elasticsearch' => [
        'index' => 'wxappr',
        'hosts' => [
            '127.0.0.1:9200'
        ],
    ],

    'mail' => [
        'fromName'  => 'loopnode',
        'fromEmail' => 'system@wxappr.com',
        'smtp' => [
            'server' => 'smtpdm.aliyun.com',
            'port' => '465',
            'security' => 'SSL',
            'username' => 'system@wxappr.com',
            'password' => ''
        ]
    ],

    'logger' => [
        'path'     => BASE_DIR . 'app/logs/',
        'format'   => '[%date%] ' . HOSTNAME . ' php: [%type%] %message%',
        'date'     => 'd-M-Y H:i:s',
        'logLevel' => Logger::WARNING,
        'filename' => 'application.log',
    ],

    'error' => [
        'logger'    => BASE_DIR . 'app/logs/error.log',
        'formatter' => [
            'format' => '[%date%] ' . HOSTNAME . ' php: [%type%] %message%',
            'date'   => 'd-M-Y H:i:s',
        ],
        'controller' => 'error',
        'action'     => 'index',
    ],

    'analytics' => [
        'enabled' => false
    ],

    'defaultTimezone' => 'Asia/Shanghai'
]);
