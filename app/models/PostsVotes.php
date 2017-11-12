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
 * Class PostsVotes
 *
 * @property \Phosphorum\Models\Posts post
 * @property \Phosphorum\Models\Users user
 *
 * @package Phosphorum\Models
 */
class PostsVotes extends Model
{
    const VOTE_UP = 1;

    const VOTE_DOWN = 2;

    public $id;

    public $posts_id;

    public $users_id;

    public $vote_type;

    public $created_at;

    public function initialize()
    {
        $this->belongsTo(
            'posts_id',
            'Phosphorum\Models\Posts',
            'id',
            ['alias' => 'post']
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

    public function afterSave()
    {
        if ($this->id) {
            $viewCache = $this->getDI()->getShared('viewCache');
            $viewCache->delete('post-' . $this->posts_id);
        }
    }

    public function afterCreate()
    {
        /**
         * Register a new activity
         */
        if ($this->id > 0) {
            $activity           = new Activities();
            $activity->users_id = $this->users_id;
            $activity->posts_id = $this->posts_id;
            $activity->type     = Activities::VOTE_UP_POST;
            $activity->save();
        }
    }
}
