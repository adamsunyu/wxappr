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
use Phosphorum\Models\Money;

/**
 * Class UsersBankbook
 *
 * @property \Phosphorum\Models\Users user
 *
 * @package Phosphorum\Models
 */
class UsersBankbook extends Model
{
    public $id;

    public $users_id;

    public $users_id_other;

    public $posts_id;

    public $posts_replies_id;

    public $income;

    public $expense;

    public $balance;

    public $type;

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

        $this->belongsTo(
            'users_id_other',
            'Phosphorum\Models\Users',
            'id',
            [
                'alias'    => 'otherUser',
                'reusable' => true
            ]
        );

        $this->addBehavior(
            new Timestampable([
                'beforeValidationOnCreate' => ['field' => 'created_at']
            ])
        );
    }

    public function getFormalCreatedAt()
    {
        return date('Y-m-d H:i:s', $this->created_at);
    }

    public function getMoneyInfo()
    {
        $change = '';

        if ($this->income > 0) {
            $change = '+' . $this->income . '微币';
        } else if($this->expense > 0) {
            $change = '-' . $this->expense . '微币';
        }

        return $change;
    }

    public function getBasicInfo()
    {
        $info = '';

        if ($this->income > 0) {
            $info = '收入';
        } else if($this->expense > 0) {
            $info = '支出';
        }

        return $info;
    }

    private function getContentLink()
    {
        $contentLink = '';

        if ($this->posts_id != null && $this->posts_replies_id != null) {
            $contentLink = '<a href="/topic/' . $this->posts_id . '#C' . $this->posts_replies_id . '">回复</a>';
        }
        if ($this->posts_id != null && $this->posts_replies_id == null) {
            $contentLink = '<a href="/topic/' . $this->posts_id . '">话题</a>';
        }

        return $contentLink;
    }

    public function getDetailInfo()
    {
        $detailInfo = '';

        switch($this->type) {

            case Money::INITIAL_INCOME:

                $detailInfo = '新用户奖励';
                break;

            case Money::DAILY_INCOME:

                $detailInfo = '24小时活跃榜奖励';
                break;

            case Money::POST_NEW:

                $detailInfo = '发布主题奖励';
                break;

            case Money::POST_REPLY:

                $detailInfo = '回复主题奖励';
                break;

            case Money::POST_STICKY:

                $detailInfo = '主题置顶奖励';
                break;

            case Money::SELF_STICKY:

                $detailInfo = '主题自助置顶';
                break;

            case Money::DELETE_POST:

                $detailInfo = '自主删除主题';
                break;

            case Money::DELETE_REPLY:

                $detailInfo = '自主删除回复';
                break;

            case Money::MODERATE_DELETE_POST:

                $detailInfo = '管理员删除主题';
                break;

            case Money::MODERATE_DELETE_REPLY:

                $detailInfo = '管理员删除回复';
                break;

            case Money::THANKS_SEND:

                $userLink = '<a href="/user/'.$this->otherUser->login.'">' .  $this->otherUser->name . '</a>';
                $contentLink = $this->getContentLink();
                $detailInfo = '感谢用户' . $userLink . '的' . $contentLink;
                break;

            case Money::THANKS_GET:

                $userLink = '<a href="/user/'.$this->otherUser->login.'">' .  $this->otherUser->name . '</a>';
                $contentLink = $this->getContentLink();
                $detailInfo = '用户' . $userLink . '感谢了你的' . $contentLink;
                break;
        }

        return $detailInfo;
    }
}
