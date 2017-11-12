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

use DateTime;
use DateTimeZone;
use Phalcon\Diff;
use Phalcon\Mvc\Model;
use Phosphorum\Utils\HumanTime;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Diff\Renderer\Html\SideBySide;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Mvc\Model\Behavior\Timestampable;

/**
 * Class Posts
 *
 * @property Users user
 * @property Nodes node
 * @property Simple replies
 * @property Simple views
 * @property Simple pollOptions
 * @property Simple pollVotes
 *
 * @method static Posts findFirstById(int $id)
 * @method static Posts findFirstByNodesId(int $id)
 * @method static Simple findByNodesId(int $id)
 * @method static Posts findFirst($parameters = null)
 * @method static Posts[] find($parameters = null)
 * @method static int countByUsersId(int $userId)
 * @method int countSubscribers($parameters = null)
 * @method Simple getReplies($parameters = null)
 * @method Simple getViews($parameters = null)
 * @method Simple getPollOptions($parameters = null)
 * @method Simple getPollVotes($parameters = null)
 *
 * @package Phosphorum\Models
 */
class Posts extends Model
{
    const IS_DELETED = 1;
    const IS_STICKED = 'Y';
    const IS_UNSTICKED = 'N';

    public $id;

    public $users_id;

    public $nodes_id;

    public $title;

    public $link;

    public $slug;

    public $content;

    public $number_views;

    public $number_replies;

    public $votes_up;

    public $votes_down;

    public $sticked;

    public $sticked_endtime;

    public $sticked_owner;

    public $modified_at;

    public $created_at;

    public $edited_at;

    public $status;

    public $locked;

    public $deleted;

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
            'sticked_owner',
            'Phosphorum\Models\Users',
            'id',
            [
                'alias'    => 'stickOwner',
                'reusable' => true
            ]
        );

        $this->belongsTo(
            'nodes_id',
            'Phosphorum\Models\Nodes',
            'id',
            [
                'alias'    => 'node',
                'reusable' => true
            ]
        );

        $this->hasMany(
            'id',
            'Phosphorum\Models\PostsReplies',
            'posts_id',
            [
                'alias' => 'replies'
            ]
        );

        $this->hasMany(
            'id',
            'Phosphorum\Models\PostsViews',
            'posts_id',
            [
                'alias' => 'views'
            ]
        );

        $this->addBehavior(
            new SoftDelete(
                [
                    'field' => 'deleted',
                    'value' => self::IS_DELETED
                ]
            )
        );

        $this->addBehavior(
            new Timestampable(
                [
                    'beforeCreate' => [
                        'field' => ['created_at', 'modified_at'],
                    ]
                ]
            )
        );
    }

    public function beforeValidationOnCreate()
    {
        $this->deleted         = 0;
        $this->number_views    = 0;
        $this->number_replies  = 0;
        $this->sticked         = self::IS_UNSTICKED;
        $this->locked          = 'N';
        $this->status          = 'A';

        if ($this->title && !$this->slug) {
            $this->slug = $this->getDI()->getShared('slug')->generate($this->title);
        }
    }

    /**
     * Create a posts-views logging the ipaddress where the post was created
     * This avoids that the same session counts as post view
     */
    public function beforeCreate()
    {
        $this->views = new PostsViews([
            'ipaddress' => $this->getDI()->getShared('request')->getClientAddress(),
        ]);
    }

    public function afterCreate()
    {
        /**
         * Register a new activity
         */
        if ($this->id > 0) {
            /**
             * Register the activity
             */
            $activity           = new Activities();
            $activity->users_id = $this->users_id;
            $activity->posts_id = $this->id;
            $activity->type     = Activities::NEW_POST;
            $activity->save();

            /**
             * Notify users that always want notifications
             */
            $parameters       = [
                 'users_id = ?0 AND posts_id = ?1',
                 'bind' => [$this->users_id, $this->posts_id]
            ];
            $hasNotifications = PostsNotifications::count($parameters);

            if (!$hasNotifications) {
                $notification           = new PostsNotifications();
                $notification->users_id = $this->users_id;
                $notification->posts_id = $this->id;
                $notification->save();
            }
        }
    }

    public function afterSave()
    {
        $this->clearCache();

        // In case of updating post through creating PostsViews
        if (!$this->getDI()->getShared('session')->has('identity')) {
            return;
        }

        $history = new PostsHistory([
            'posts_id' => $this->id,
            'users_id' => $this->getDI()->getShared('session')->get('identity'),
            'content'  => $this->content,
        ]);

        if (!$history->save()) {
            /** @var \Phalcon\Logger\AdapterInterface $logger */
            $logger   = $this->getDI()->get('logger');
            $messages = $history->getMessages();
            $reason   = [];

            foreach ($messages as $message) {
                /** @var \Phalcon\Mvc\Model\MessageInterface $message */
                $reason[] = $message->getMessage();
            }

            $logger->error('Unable to store post history. Post id: {id}. Reason: {reason}', [
                'id'     => $this->id,
                'reason' => implode('. ', $reason)
            ]);
        }
    }

    public function afterDelete()
    {
        $this->clearCache();
    }

    /**
     * Returns a W3C date to be used in the sitemap.
     *
     * @return string
     */
    public function getUTCModifiedAt()
    {
        $modifiedAt = new DateTime('@' . $this->modified_at, new DateTimeZone('UTC'));

        return $modifiedAt->format('Y-m-d\TH:i:s\Z');
    }

    /**
     * @return array
     */
    public function getRecentUsers()
    {
        $users  = [];
        foreach ($this->getReplies(['order' => 'created_at DESC', 'limit' => 1]) as $reply) {
            if (!isset($users[$reply->user->id])) {
                $users[$reply->user->id] = [$reply->user->login, $reply->user];
            }
        }
        return $users;
    }

    /**
     * @return string
     */
    public function getHumanNumberViews()
    {
        $number = $this->number_views;
        if ($number > 1000) {
            return round($number / 1000, 1) . 'k';
        } else {
            return $number;
        }
    }

    /**
     * @return bool|string
     */
    public function getHumanCreatedAt()
    {
        return HumanTime::getHumanYearLevel($this->created_at);
    }

    /**
     * @return bool|string
     */
    public function getLastActiveTime()
    {
        $activeTime = '';

        if ($this->edited_at > $this->modified_at) {
            $activeTime =  HumanTime::getHumanYearLevel($this->edited_at).'<b class="post-date-action">发</b>';
        } else if($this->modified_at > $this->created_at) {
            $activeTime =  HumanTime::getHumanYearLevel($this->modified_at).'<b class="post-date-action">回</b>';
        } else {
            $activeTime =  HumanTime::getHumanYearLevel($this->created_at).'<b class="post-date-action">发</b>';
        }

        return $activeTime;
    }

    /**
     * @return bool|string
     */
    public function getHumanEditedAt()
    {
        return HumanTime::getHumanYearLevel($this->edited_at);
    }

    /**
     * @return bool|string
     */
    public function getHumanModifiedAt()
    {
        if ($this->modified_at != $this->created_at) {
            return HumanTime::getHumanYearLevel($this->modified_at);
        }

        return false;
    }

    /**
     * Checks if the post can have a bounty
     *
     * @return boolean
     */
    public function canHaveBounty()
    {
        $canHave = $this->sticked != self::IS_STICKED
            && $this->number_replies == 0
            && //show community
            ($this->votes_up - $this->votes_down) >= 0;

        if ($canHave) {
            $diff = time() - $this->created_at;
            if ($diff > 86400 && $diff < (86400 * 30)) {
                return true;
            } elseif ($diff < 3600) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculates a bounty for the post
     *
     * @return array|bool
     */
    public function getBounty()
    {
        $diff = time() - $this->created_at;
        if ($diff > 86400) {
            if ($diff < (86400 * 30)) {
                return ['type' => 'old', 'value' => 150 + intval($diff / 86400 * 3)];
            }
        } elseif ($diff < 3600) {
            return ['type' => 'fast-reply', 'value' => 100];
        }

        return false;
    }

    /**
     * Checks if the Post has replies
     *
     * @return bool
     */
    public function hasReplies()
    {
        return $this->number_replies > 0;
    }

    /**
     * Checks if the Post has a Poll
     *
     * @return bool
     */
    public function hasPoll()
    {
        return $this->getPollOptions()->valid();
    }

    /**
     * Checks if User is participated in a Poll
     *
     * @param int $userId User ID
     * @return bool
     */
    public function isParticipatedInPoll($userId)
    {
        if (!$userId) {
            return false;
        }

        return $this->getPollVotes(['users_id = :id:', 'bind' => ['id' => $userId]])->valid();
    }

    /**
     * Checks if the voting for the poll was started
     *
     * @return bool
     */
    public function isStartVoting()
    {
        return $this->getPollVotes()->count() > 0;
    }

    /**
     * Checks whether a specific user is subscribed to the post
     *
     * @param int $userId
     * @return bool
     */
    public function isSubscribed($userId)
    {
        return $this->countSubscribers(['users_id = :userId:', 'bind' => ['userId' => $userId]]) > 0;
    }

    /**
     * Clears the cache related to this post
     */
    public function clearCache()
    {
        if ($this->id) {
            $viewCache = $this->getDI()->getShared('viewCache');
            $viewCache->delete('post-' . $this->id);
            $viewCache->delete('post-body-' . $this->id);
            $viewCache->delete('post-users-' . $this->id);
            $viewCache->delete('sidebar');
        }
    }

    public function getDifference()
    {
        $history = PostsHistory::findLast($this);

        if (!$history->valid()) {
            return false;
        }

        if ($history->count() > 1) {
            $history = $history->offsetGet(1);
        } else {
            $history = $history->getFirst();
        }

        /** @var PostsHistory $history */

        $b = explode("\n", $history->content);

        $diff = new Diff($b, explode("\n", $this->content), []);
        $difference = $diff->render(new SideBySide);

        return $difference;
    }

    public function getRemainStickTime()
    {
        $now = new DateTime(date('Y-m-d H:i:s'));
        $end = new DateTime(date('Y-m-d H:i:s', $this->sticked_endtime));

        $interval = $now->diff($end);

        return ($interval->d * 24 + $interval->h) . '小时';
    }
}
