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
 * Class Activities
 *
 * @property \Phosphorum\Models\Users user
 * @property \Phosphorum\Models\Posts post
 *
 * @package Phosphorum\Models
 */
class Activities extends Model
{
    public $id;
    public $users_id;
    public $type;
    public $posts_id;
    public $posts_replies_id;
    public $follow_user_id;
    public $created_at;

    const NEW_POST = 'NP';
    const NEW_REPLY = 'NR';
    const VOTE_UP_POST = 'VP';
    const VOTE_UP_REPLY = 'VR';

    const FOLLOW_USER = 'FU';

    const APP_REVIEW = 'AR';

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

        $this->belongsTo(
            'follow_user_id',
            'Phosphorum\Models\Users',
            'id',
            [
                'alias'    => 'followUser',
                'reusable' => true
            ]
        );

        $this->belongsTo(
            'posts_id',
            'Phosphorum\Models\Posts',
            'id',
            [
                'alias'    => 'post',
                'reusable' => true
            ]
        );

        $this->addBehavior(
            new Timestampable([
                'beforeCreate' => [
                    'field' => 'created_at'
                ]
            ])
        );
    }

    /**
     * @return bool|string
     */
    public function getHumanCreatedAt()
    {
        return HumanTime::getHumanDayLevel($this->created_at);
    }
}
