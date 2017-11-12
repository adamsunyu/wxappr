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
use Phosphorum\Models\Money;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use Phosphorum\Models\UsersBankbook;

/**
 * Phosphorum\Models\Bank
 */
class Bank extends Model
{
    static public function handleInitialIncome($user)
    {
        $today = date('Y-m-d');
        $income = Money::NUM_INITIAL_INCOME;

        $user->money_date = $today;
        $user->money += $income;

        $bankLog = new UsersBankbook();
        $bankLog->users_id = $user->id;
        $bankLog->income   = $income;
        $bankLog->expense  = 0;
        $bankLog->balance  = $user->money;
        $bankLog->type     = Money::INITIAL_INCOME;

        if($user->save() && $bankLog->save()) {

            $activity                     = new ActivityNotifications();
            $activity->users_id           = $user->id;
            $activity->posts_id           = 0;
            $activity->users_origin_id    = 0;
            $activity->type               = Money::INITIAL_INCOME;
            $activity->extra              = $income;
            $activity->save();
        }
    }

    static public function handleDailyIncome($user)
    {
        $today = date('Y-m-d');

        if($today != $user->money_date) {

            $user->money_date = $today;
            $user->save();

            $today = date('Y-m-d');
            $income = rand(1, Money::NUM_DAILY_INCOME);

            $user->money_date = $today;
            $user->money += $income;

            $bankLog = new UsersBankbook();
            $bankLog->users_id = $user->id;
            $bankLog->income   = $income;
            $bankLog->expense  = 0;
            $bankLog->balance  = $user->money;
            $bankLog->type = Money::DAILY_INCOME;

            if($user->save() && $bankLog->save()) {

                $activity                       = new ActivityNotifications();
                $activity->users_id             = $user->id;
                $activity->posts_id             = 0;
                $activity->users_origin_id      = 0;
                $activity->type                 = Money::DAILY_INCOME;
                $activity->extra                = $income;
                $activity->save();
            }
        }
    }

    static public function handlePostNew($user)
    {
        $income = Money::NUM_POST_NEW;

        $user->money += $income;

        $bankLog = new UsersBankbook();
        $bankLog->users_id = $user->id;
        $bankLog->income = $income;
        $bankLog->expense = 0;
        $bankLog->balance = $user->money;
        $bankLog->type = Money::POST_NEW;

        if ($bankLog->save()) {
            $user->save();
        }
    }

    static public function handlePostReply($user, $isFirstReply)
    {
        $income = Money::NUM_POST_REPLY;
        $user->money += $income;

        if ($isFirstReply) {
            $bonus = rand(1, Money::NUM_POST_REPLY);
            $income += $bonus;
            $user->money += $bonus;
        }

        $bankLog = new UsersBankbook();
        $bankLog->users_id = $user->id;
        $bankLog->income = $income;
        $bankLog->expense = 0;
        $bankLog->balance = $user->money;
        $bankLog->type = Money::POST_REPLY;

        if ($bankLog->save()) {
            $user->save();
        }
    }

    static public function handlePostSticky($user)
    {
        $income = Money::NUM_POST_STICKY;

        $user->money += $income;

        $bankLog = new UsersBankbook();
        $bankLog->users_id = $user->id;
        $bankLog->income = $income;
        $bankLog->expense = 0;
        $bankLog->balance = $user->money;
        $bankLog->type = Money::POST_STICKY;

        if($user->save() && $bankLog->save()) {

            $activity                       = new ActivityNotifications();
            $activity->users_id             = $user->id;
            $activity->posts_id             = 0;
            $activity->users_origin_id      = 0;
            $activity->type                 = Money::POST_STICKY;
            $activity->extra                = $income;
            $activity->save();
        }
    }

    static public function handleSelfSticky($user, $stickAmount)
    {
        $expense = $stickAmount;

        $user->money -= $expense;

        $bankLog = new UsersBankbook();
        $bankLog->users_id = $user->id;
        $bankLog->income = 0;
        $bankLog->expense = $expense;
        $bankLog->balance = $user->money;
        $bankLog->type = Money::SELF_STICKY;

        if($user->save() && $bankLog->save()) {
            // Do nothing
        }
    }


    static public function handleDeletePost($user)
    {
        $expense = Money::NUM_DELETE_POST;

        $user->money -= $expense;

        $bankLog = new UsersBankbook();
        $bankLog->users_id = $user->id;
        $bankLog->income = 0;
        $bankLog->expense = $expense;
        $bankLog->balance = $user->money;
        $bankLog->type = Money::DELETE_POST;

        if ($bankLog->save()) {
            $user->save();
        }
    }

    static public function handleDeleteReply($user)
    {
        $expense = Money::NUM_DELETE_REPLY;

        $user->money -= $expense;

        $bankLog = new UsersBankbook();
        $bankLog->users_id = $user->id;
        $bankLog->income = 0;
        $bankLog->expense = $expense;
        $bankLog->balance = $user->money;
        $bankLog->type = Money::DELETE_REPLY;

        if ($bankLog->save()) {
            $user->save();
        }
    }

    static public function handleModeratePost($user, $post)
    {
        $expense = Money::NUM_MODERATE_DELETE_POST;
        $user->money -= $expense;

        $bankLog = new UsersBankbook();
        $bankLog->users_id = $user->id;
        $bankLog->income = 0;
        $bankLog->expense = $expense;
        $bankLog->balance = $user->money;
        $bankLog->type = Money::MODERATE_DELETE_POST;

        if($user->save() && $bankLog->save()) {

            $activity                       = new ActivityNotifications();
            $activity->users_id             = $user->id;
            $activity->posts_id             = $post->id;
            $activity->users_origin_id      = 0;
            $activity->type                 = Money::MODERATE_DELETE_POST;
            $activity->extra                = $expense;
            $activity->save();
        }
    }

    static public function handleModerateReply($user, $postReply)
    {
        $expense = Money::NUM_MODERATE_DELETE_REPLY;
        $user->money -= $expense;

        $bankLog = new UsersBankbook();
        $bankLog->users_id = $user->id;
        $bankLog->income = 0;
        $bankLog->expense = $expense;
        $bankLog->balance = $user->money;
        $bankLog->type = Money::MODERATE_DELETE_REPLY;

        if($user->save() && $bankLog->save()) {

            $activity                       = new ActivityNotifications();
            $activity->users_id             = $user->id;
            $activity->posts_id             = $postReply->post->id;
            $activity->posts_replies_id     = $postReply->id;
            $activity->users_origin_id      = 0;
            $activity->type                 = Money::MODERATE_DELETE_REPLY;
            $activity->extra                = $expense;
            $activity->save();
        }
    }
}
