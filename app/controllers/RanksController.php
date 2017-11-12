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

use Phalcon\Http\Response;
use Phosphorum\Models\Users;
use Phosphorum\Models\Posts;
use Phosphorum\Models\Nodes;
use Phosphorum\Models\UsersFollowers;
use Phosphorum\Models\UsersActivities;
use Phosphorum\Utils\HumanTime;
use Phosphorum\Utils\TokenTrait;

use Phalcon\Tag;

use DateTime;
use DateInterval;

/**
 * Class RanksController
 *
 * @package Phosphorum\Controllers
 */
class RanksController extends ControllerBase
{
    use TokenTrait;

    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Shows users rank list
     *
     * @param string $slug
     * @param int  $offset
     */
    public function indexAction($tab = 'getvotes', $offset = 0)
    {
        $this->tag->setTitle('用户列表');

        $itemBuilder = $this
            ->modelsManager
            ->createBuilder()
            ->from(['n' => 'Phosphorum\Models\Users']);

        $totalBuilder = $this
            ->modelsManager
            ->createBuilder()
            ->from(['n' => 'Phosphorum\Models\Users']);

        $itemBuilder
            ->columns(['n.*'])
            ->limit(self::POSTS_IN_PAGE);

        $totalBuilder
            ->columns('COUNT(*) AS count');

        if ($tab == 'getvotes') {
            $itemBuilder->orderBy('n.votes_receive DESC');
        } else if($tab == 'sendvotes') {
            $itemBuilder->orderBy('n.votes_send DESC');
        } else if($tab == 'wealth') {
            $itemBuilder->orderBy('n.money DESC');
        }

        if ($offset > 0) {
            $itemBuilder->offset((int)$offset);
        }

        $number = $totalBuilder->getQuery()->setUniqueRow(true)->execute();
        $userList  = $itemBuilder->getQuery()->execute();
        $totalUsers = $number->count;

        parent::initPublicSidebar();

        $this->view->setVars([
            'users'        => $userList,
            'totalUsers'   => $totalUsers,
            'currentTab'   => $tab,
            'offset'       => $offset,
            'paginatorUri' => "users/{$slug}"
        ]);
    }
}
