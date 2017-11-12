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
use Phalcon\Mvc\Model\Behavior\SoftDelete;

/**
 * Phosphorum\Models\MessagesInbox
 */
class MessagesInbox extends Model
{
    const IS_DELETED = 1;

    public $id;

    public $users_id;

    public $users_origin_id;

    public $content;

    public $created_at;

    public $deleted;

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

        $this->addBehavior(
            new Timestampable(
                [
                    'beforeCreate' => [
                        'field' => ['created_at'],
                    ]
                ]
            )
        );

        $this->addBehavior(
            new SoftDelete(
                [
                    'field' => 'deleted',
                    'value' => self::IS_DELETED
                ]
            )
        );
    }

    public function getHumanCreatedAt()
    {
        return HumanTime::getHumanDayLevel($this->created_at);
    }

    public function markAsRead()
    {
        if ($this->was_read == 'N') {
            $this->was_read = 'Y';
            $this->save();
        }
    }
}
