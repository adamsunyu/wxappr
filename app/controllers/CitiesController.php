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
use Phosphorum\Models\Cities;
use Phosphorum\Models\Posts;
use Phosphorum\Utils\TokenTrait;

use Phalcon\Tag;

use DateTime;
use DateInterval;

/**
 * Class UsersController
 *
 * @package Phosphorum\Controllers
 */
class CitiesController extends ControllerBase
{
    use TokenTrait;

    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Shows users city list
     *
     * @param string $slug
     * @param int  $offset
     */
    public function indexAction($citySlug = 'beijing', $offset = 0)
    {
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
            ->limit(self::POSTS_IN_PAGE)
            ->orderBy('last_activity DESC');

        $totalBuilder
            ->columns('COUNT(*) AS count');

        if ($offset > 0) {
            $itemBuilder->offset((int)$offset);
        }

        $city = Cities::findFirstBySlug($citySlug);

        if ($city) {
            $itemBuilder->where('city_id = ' . $city->id);
            $totalBuilder->where('city_id = ' . $city->id);
        }

        if ($city) {
            $this->tag->setTitle($city->name . '会员');
        } else {
            $this->tag->setTitle('其它城市会员');
        }

        $number = $totalBuilder->getQuery()->setUniqueRow(true)->execute();
        $userList  = $itemBuilder->getQuery()->execute();
        $totalUsers = $number->count;

        $parameters = ["limit" => 7, "columns" => "id, name, slug, number_users", "order" => "rank ASC"];

        $cityList = Cities::find($parameters);

        parent::initPublicSidebar();

        $this->view->setVars([
            'users'        => $userList,
            'city'         => $city,
            'cityList'     => $cityList,
            'totalUsers'   => $totalUsers,
            'currentTab'   => $citySlug,
            'offset'       => $offset,
            'paginatorUri' => "users/{$citySlug}"
        ]);
    }
}
