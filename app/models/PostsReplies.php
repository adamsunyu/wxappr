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
 * Class PostsReplies
 *
 * @property \Phosphorum\Models\Posts        post
 * @property \Phosphorum\Models\PostsReplies postReplyTo
 * @property \Phosphorum\Models\Users        user
 *
 * @method static PostsReplies findFirstById(int $id)
 * @method static PostsReplies findFirst($parameters = null)
 * @method static PostsReplies[] find($parameters = null)
 *
 * @package Phosphorum\Models
 */
class PostsReplies extends Model
{

    public $id;

    public $posts_id;

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
            'posts_id',
            'Phosphorum\Models\Posts',
            'id',
            [
                'alias'    => 'post',
                'reusable' => true
            ]
        );

        $this->belongsTo(
            'in_reply_to_id',
            'Phosphorum\Models\PostsReplies',
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
            } elseif ($postReplyTo->posts_id != $this->posts_id) {
                $this->in_reply_to_id = 0;
            }
        }
    }

    public function afterCreate()
    {
        if ($this->id > 0) {

            $activity           = new Activities();
            $activity->users_id = $this->users_id;
            $activity->posts_id = $this->posts_id;
            $activity->type     = Activities::NEW_REPLY;
            $activity->save();

            /**
             * Register the user in the post's notifications
             */
            $parameters       = [
                'users_id = ?0 AND posts_id = ?1',
                'bind' => [$this->users_id, $this->posts_id]
            ];
            $hasNotifications = PostsNotifications::count($parameters);

            if (!$hasNotifications) {
                $notification           = new PostsNotifications();
                $notification->users_id = $this->users_id;
                $notification->posts_id = $this->posts_id;
                $notification->save();
            }

            /**
             * Notify users that have commented in the same post
             */
            $postsNotifications = PostsNotifications::findByPostsId($this->posts_id);

            foreach ($postsNotifications as $postNotification) {
                if ($postNotification->users_id != $this->users_id) {

                    $activity                       = new ActivityNotifications();
                    $activity->users_id             = $postNotification->users_id;
                    $activity->posts_id             = $this->posts_id;
                    $activity->posts_replies_id     = $this->id;
                    $activity->users_origin_id      = $this->users_id;
                    $activity->type                 = ActivityNotifications::NEW_REPLY;
                    $activity->save();
                }
            }
        }
    }

    public function afterSave()
    {
        $this->clearCache();

        $history                   = new PostsRepliesHistory();
        $history->posts_replies_id = $this->id;
        $usersId                   = $this->getDI()->getSession()->get('identity');
        $history->users_id         = $usersId ? $usersId : $this->users_id;
        $history->content          = $this->content;

        $history->save();
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
            $viewCache = $this->getDI()->getShared('viewCache');
            $viewCache->delete('post-' . $this->posts_id);
            $viewCache->delete('post-body-' . $this->posts_id);
            $viewCache->delete('post-users-' . $this->posts_id);
            $viewCache->delete('reply-body-' . $this->id);
        }
    }

    public function getDifference()
    {
        $history = PostsRepliesHistory::findLast($this);

        if (!$history->valid()) {
            return false;
        }

        if ($history->count() > 1) {
            $history = $history->offsetGet(1);
        } else {
            $history = $history->getFirst();
        }

        /** @var PostsRepliesHistory $history */

        $b = explode("\n", $history->content);

        $diff = new Diff($b, explode("\n", $this->content), []);
        $difference = $diff->render(new SideBySide);

        return $difference;
    }
}
