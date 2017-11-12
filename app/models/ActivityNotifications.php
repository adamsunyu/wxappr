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
 * Class ActivityNotifications
 *
 * @property \Phosphorum\Models\Users        user
 * @property \Phosphorum\Models\Posts        post
 * @property \Phosphorum\Models\PostsReplies reply
 *
 * @package Phosphorum\Models
 */
class ActivityNotifications extends Model
{
    const VOTE_UP_POST  = 'VP';
    const VOTE_UP_REPLY = 'VR';
    const USER_FOLLOW   = 'UF';
    const GOT_MESSAGE   = 'GM';
    const NEW_REPLY     = 'NR';

    // 特别注明：和微币相关的通知使用Money里的常量

    public $id;
    public $users_id;
    public $users_origin_id;

    public $type;

    public $posts_id;

    public $posts_replies_id;

    public $created_at;

    public $was_read;

    public $extra;

    public function beforeValidationOnCreate()
    {
        $this->was_read = 'N';
    }

    public function initialize()
    {
        $this->belongsTo(
            'users_id',
            'Phosphorum\Models\Users',
            'id',
            [
                'alias' => 'user'
            ]
        );

        $this->belongsTo(
            'users_origin_id',
            'Phosphorum\Models\Users',
            'id',
            [
                'alias' => 'userOrigin'
            ]
        );

        $this->belongsTo(
            'posts_id',
            'Phosphorum\Models\Posts',
            'id',
            [
                'alias' => 'post'
            ]
        );

        $this->belongsTo(
            'posts_replies_id',
            'Phosphorum\Models\PostsReplies',
            'id',
            [
                'alias' => 'reply'
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

    public function markAsRead()
    {
        if ($this->was_read == 'N') {
            $this->was_read = 'Y';
            $this->save();
        }
    }

    /**
     * @return bool|string
     */
    public function getHumanCreatedAt()
    {
        return HumanTime::getHumanDayLevel($this->created_at);
    }
}
