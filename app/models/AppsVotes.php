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

/**
 * Class AppsVotes
 *
 * @property \Phosphorum\Models\Apps app
 * @property \Phosphorum\Models\Users user
 *
 * @package Phosphorum\Models
 */
class AppsVotes extends Model
{
    const VOTE_UP   = 1;

    const VOTE_DOWN = 2;

    const VOTE_FUN  = 3;

    public $id;

    public $apps_id;

    public $users_id;

    public $vote_type;

    public $created_at;

    public function initialize()
    {
        $this->belongsTo(
            'apps_id',
            'Phosphorum\Models\Apps',
            'id',
            ['alias' => 'app']
        );

        $this->belongsTo(
            'users_id',
            'Phosphorum\Models\Users',
            'id',
            ['alias' => 'user']
        );

        $this->addBehavior(
            new Timestampable([
                'beforeValidationOnCreate' => ['field' => 'created_at']
            ])
        );
    }

    public function afterCreate()
    {
        // /**
        //  * Register a new activity
        //  */
        // if ($this->id > 0) {
        //     $activity           = new Activities();
        //     $activity->users_id = $this->users_id;
        //     $activity->posts_id = $this->posts_id;
        //     $activity->type     = Activities::VOTE_UP_POST;
        //     $activity->save();
        // }
    }
}
