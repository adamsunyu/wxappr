<?php

/*
 +------------------------------------------------------------------------+
 | wxappr.com                                                               |
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

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phosphorum\Models\Posts;
use Phosphorum\Models\Cities;
use Phosphorum\Models\PostsReplies;
use Phosphorum\Models\Users;
use Phalcon\Mvc\Dispatcher;
use Phosphorum\Models\ActivityNotifications;
use Phosphorum\Models\UsersActivities;
use Phosphorum\Models\Bank;

/**
 * Class ControllerBase
 *
 * @package Phosphorum\Controllers
 *
 * @property \Phalcon\Cache\BackendInterface viewCache
 * @property \Phalcon\Config config
 * @property \Phosphorum\Utils\Slug slug
 * @property \Phalcon\Logger\AdapterInterface logger
 * @property \Phalcon\Breadcrumbs breadcrumbs
 * @property \Phosphorum\Utils\Security $security
 */
class ControllerBase extends Controller
{
    const POSTS_IN_PAGE = 50;

    public $myself = null;

    public function onConstruct()
    {
        $this->view->setVars([
            'app_name'       => $this->config->get('site')->name,
            'app_prefix'     => $randPrefix,
            'app_version'    => VERSION,
            'actionName'     => $this->dispatcher->getActionName(),
            'controllerName' => $this->dispatcher->getControllerName(),
        ]);
    }

    /**
     * This initializes the timezone in each request
     */
    public function initialize()
    {
        if ($timezone = $this->session->get('identity-timezone')) {
            date_default_timezone_set($timezone);
        }

        $this->view->setVar('limitPost', self::POSTS_IN_PAGE);
    }

    public function initPublicSidebar()
    {
        $statInfo = [Users::count(), Posts::count(), PostsReplies::count(), (Cities::count() - 1)];
        $activeUsers = UsersActivities::find(["order" => "page_views DESC", "limit" => 10]);

        $this->view->setVars([
            'statistic'   => $statInfo,
            'activeUsers' => $activeUsers
        ]);
    }

    public function initUsercSidebar()
    {

    }

    /**
     * Execute before the router so we can determine if this is a private controller, and must be authenticated, or a
     * public controller that is open to all.
     *
     * @param Dispatcher $dispatcher
     * @return boolean
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $usersId = $this->auth->getIdentity();

        if ($usersId) {

            $user = Users::findFirstById($usersId);

            if ($user) {
                $this->myself = $user;
                $this->view->setVar('myself', $user);
            }

            $userActivity = UsersActivities::findFirstByUsersId($usersId);

            if ($userActivity) {
                $userActivity->page_views += 1;
            } else {
                $userActivity = new UsersActivities();
                $userActivity->users_id = $usersId;
                $userActivity->page_views = 1;
                $userActivity->modified_at = time();
                $userActivity->created_at = time();
            }

            $user->last_activity = time();
            $user->save();
            $userActivity->save();
        } else {
            if ($this->auth->hasRememberMe()) {
                $this->auth->loginWithRememberMe();
            }
        }
    }

    public function checkUserLogin()
    {
        if (!$usersId = $this->session->get('identity')) {
            return;
        }
        $user = Users::findFirstById($usersId);
        if (!$user) {
            $this->flashSession->error('用户不存在');
            return;
        }
        return $user;
    }
}
