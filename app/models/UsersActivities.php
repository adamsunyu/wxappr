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

namespace Phosphorum\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use Phosphorum\Utils\HumanTime;

/**
 * Class UsersActivities
 *
 * @package Phosphorum\Models
 */
class UsersActivities extends Model
{
    public $id;

    public $users_id;

    public $page_views;

    public $modified_at;

    public $created_at;

    public function initialize()
    {
        $this->belongsTo(
            'users_id',
            'Phosphorum\Models\Users',
            'id',
            [
                'alias'    => 'user',
                'reusable' => true
            ]
        );

        $this->addBehavior(
            new Timestampable([
                'beforeCreate' => ['field' => 'created_at'],
                'beforeUpdate' => ['field' => 'modified_at']
            ])
        );
    }

    public function getHumanModifiedAt()
    {
        return HumanTime::getHumanDayLevel($this->modified_at);
    }
}
