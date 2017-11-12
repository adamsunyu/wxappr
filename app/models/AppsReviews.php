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

use Phalcon\Diff;
use Phalcon\Mvc\Model;
use Phalcon\Diff\Renderer\Html\SideBySide;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use Phosphorum\Utils\HumanTime;

/**
 * Class AppsReviews
 *
 * @property \Phosphorum\Models\Apps        app
 * @property \Phosphorum\Models\AppsReviews appReplyTo
 * @property \Phosphorum\Models\Users       user
 *
 * @method static PostsReplies findFirstById(int $id)
 * @method static PostsReplies findFirst($parameters = null)
 * @method static PostsReplies[] find($parameters = null)
 *
 * @package Phosphorum\Models
 */
class AppsReviews extends Model
{

    public $id;

    public $apps_id;

    public $users_id;

    public $in_reply_to_id;

    public $in_reply_to_user;

    public $content;

    public $votes_up;

    public $votes_down;

    public $created_at;

    public $modified_at;

    public $edited_at;

    public function initialize()
    {
        $this->belongsTo(
            'apps_id',
            'Phosphorum\Models\Apps',
            'id',
            [
                'alias'    => 'app',
                'reusable' => true
            ]
        );

        $this->belongsTo(
            'in_reply_to_id',
            'Phosphorum\Models\AppsReviews',
            'id',
            [
                'alias'    => 'postReplyTo',
                'reusable' => true
            ]
        );

        $this->belongsTo(
            'in_reply_to_user',
            'Phosphorum\Models\Users',
            'id',
            [
                'alias'    => 'replyToUser',
                'reusable' => true
            ]
        );

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
                'beforeCreate' => [
                    'field' => 'created_at'
                ],
                'beforeUpdate' => [
                    'field' => 'modified_at'
                ]
            ])
        );
    }

    public function beforeCreate()
    {
        if ($this->in_reply_to_id > 0) {
            $postReplyTo = self::findFirst(['id = ?0', 'bind' => [$this->in_reply_to_id]]);
            if (!$postReplyTo) {
                $this->in_reply_to_id = 0;
            } elseif ($postReplyTo->apps_id != $this->apps_id) {
                $this->in_reply_to_id = 0;
            }
        }
    }

    public function afterCreate()
    {
        if ($this->id > 0) {

            // $activity           = new Activities();
            // $activity->users_id = $this->users_id;
            // $activity->posts_id = $this->posts_id;
            // $activity->type     = Activities::NEW_REVIEW;
            // $activity->save();

            // /**
            //  * Register the user in the post's notifications
            //  */
            // $parameters       = [
            //     'users_id = ?0 AND posts_id = ?1',
            //     'bind' => [$this->users_id, $this->posts_id]
            // ];
            // $hasNotifications = PostsNotifications::count($parameters);
            //
            // if (!$hasNotifications) {
            //     $notification           = new PostsNotifications();
            //     $notification->users_id = $this->users_id;
            //     $notification->posts_id = $this->posts_id;
            //     $notification->save();
            // }

            // /**
            //  * Notify users that have commented in the same post
            //  */
            // $postsNotifications = PostsNotifications::findByPostsId($this->posts_id);
            //
            // foreach ($postsNotifications as $postNotification) {
            //     if ($postNotification->users_id != $this->users_id) {
            //
            //         $activity                       = new ActivityNotifications();
            //         $activity->users_id             = $postNotification->users_id;
            //         $activity->posts_id             = $this->posts_id;
            //         $activity->posts_replies_id     = $this->id;
            //         $activity->users_origin_id      = $this->users_id;
            //         $activity->type                 = ActivityNotifications::NEW_REPLY;
            //         $activity->save();
            //     }
            // }
        }
    }

    public function afterDelete()
    {
        $this->clearCache();
    }

    /**
     * @return bool|string
     */
    public function getHumanCreatedAt()
    {
        return HumanTime::getHumanDayLevel($this->created_at);
    }

    /**
     * @return bool|string
     */
    public function getHumanEditedAt()
    {
        return HumanTime::getHumanDayLevel($this->edited_at);
    }

    public function clearCache()
    {
        if ($this->id) {
        }
    }
}
