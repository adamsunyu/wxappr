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
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phosphorum\Models\UsersSocial;
use Phosphorum\Models\Money;
use Phosphorum\Utils\HumanTime;

/**
 * Class Users
 *
 * @property Simple badges
 * @property Simple posts
 * @property Simple replies
 * @method Simple getBadges($parameters=null)
 * @method Simple getPosts($parameters=null)
 * @method Simple getReplies($parameters=null)
 * @method static Users findFirstById(int $id)
 * @method static Users findFirstByLogin(string $login)
 * @method static Users findFirstByName(string $name)
 * @method static Users findFirstByEmail(string $email)
 * @method static Users findFirstByAccessToken(string $token)
 * @method static Users[] find($parameters=null)
 *
 * @package Phosphorum\Models
 */
class Users extends Model
{
    public $id;

    public $name;

    public $login;

    public $email;

    public $github_login;

    /**
     * signup_source = "E" : email signup
     * signup_source = "G" : github signup
     */
    public $signup_source;

    public $token_type;

    public $access_token;

    public $avatar_version;

    public $notifications;

    public $digest;

    public $timezone;

    public $moderator;

    public $money;

    public $money_date;

    public $votes_receive;

    public $votes_send;

    public $thanks_receive;

    public $thanks_send;

    public $number_followers;

    public $number_followings;

    public $banned;

    public $theme;

    public $password;

    public $role_id;

    public $city_id;

    public $city_name;

    public $inviter_id;

    public $suspended;

    public $active;

    public $last_activity;

    public $created_at;

    public $modified_at;

    public function initialize()
    {
        $this->hasMany(
            'id',
            'Phosphorum\Models\UsersBadges',
            'users_id',
            [
                'alias' => 'badges',
                'reusable' => true
            ]
        );

        $this->hasMany(
            'id',
            'Phosphorum\Models\Posts',
            'users_id',
            [
                'alias' => 'posts',
                'reusable' => true
            ]
        );

        $this->hasMany(
            'id',
            'Phosphorum\Models\PostsReplies',
            'users_id',
            [
                'alias' => 'replies',
                'reusable' => true
            ]
        );

        $this->belongsTo(
            'role_id',
            'Phosphorum\Models\Roles',
            'id', [
                'alias' => 'role',
                'reusable' => true
            ]
        );

        $this->hasOne(
            'cities_Id',
            'Phosphorum\Models\Cities',
            'id', [
                'alias' => 'city',
                'reusable' => true
            ]
        );

        $this->hasMany(
            'id',
            'Phosphorum\Models\LoginsSuccess',
            'usersId', [
            'alias' => 'loginsSuccess',
            'foreignKey' => [
                'message' => 'User cannot be deleted because he/she has activity in the system'
                ]
            ]
        );

        $this->hasMany(
            'id',
            'Phosphorum\Models\ChangesPassword',
            'usersId', [
                'alias' => 'changesPassword',
                'foreignKey' => [
                    'message' => 'User cannot be deleted because he/she has activity in the system'
                ]
            ]
        );

        $this->hasMany('id', __NAMESPACE__ . '\ResetPasswords', 'usersId', [
            'alias' => 'resetPasswords',
            'foreignKey' => [
                'message' => 'User cannot be deleted because he/she has activity in the system'
            ]
        ]);

        $this->addBehavior(
            new Timestampable([
                'beforeCreate' => ['field' => 'created_at'],
                'beforeUpdate' => ['field' => 'modified_at']
            ])
        );
    }

    public function beforeCreate()
    {
        $defaultTimezone = $this->di->getShared('config')->get('defaultTimezone');

        $this->notifications = 'P';
        $this->digest        = 'Y';
        $this->moderator     = 'N';
        $this->money         = 0;
        $this->votes_receive = 0;
        $this->votes_send    = 0;
        $this->timezone      = $defaultTimezone;
        $this->theme         = 'D';
        $this->banned        = 'N';
        $this->suspended     = 'N';
        $this->role_id       = 2;
        $this->last_activity = time();
    }

    /**
     * @return string
     */
    public function getHumanMoney()
    {
        if ($this->money >= 1000) {
            return sprintf("%.1f", $this->money / 1000) . 'k';
        } else {
            return $this->money;
        }
    }

    public function getUserLevel() {

        $level = 0;

        $zan = $this->votes_receive;

        if ($zan < 8) {
            $level = 0;
        } else if ($zan >= 8 && $zan < 16) {
            $level = 1;
        } else if ($zan >= 16 && $zan < 32) {
            $level = 2;
        } else if ($zan >= 32 && $zan < 64) {
            $level = 3;
        } else if ($zan >= 64 && $zan < 128) {
            $level = 4;
        } else if ($zan >= 128 && $zan < 256) {
            $level = 5;
        } else if ($zan >= 256 && $zan < 512) {
            $level = 6;
        } else if ($zan >= 512 && $zan < 1024) {
            $level = 7;
        } else if ($zan >= 1024 && $zan < 2048) {
            $level = 8;
        } else if ($zan >= 2048 && $zan < 4096) {
            $level = 9;
        } else if ($zan >= 4096 && $zan < 8192) {
            $level = 10;
        } else if ($zan >= 8192 && $zan < 16384) {
            $level = 11;
        } else if ($zan >= 16384) {
            $level = 12;
        }

        return $level;
    }

    public function getUserLevelStar() {

        $starLevel = '';

        $starIcon = '<span class="glyphicon glyphicon-star star-level-%d"></span>';

        $zan = $this->votes_receive;

        if ($zan < 8) {
            $starLevel = '无星';
        } else if ($zan >= 8 && $zan < 16) {
            $starLevel = sprintf($starIcon, 1);
        } else if ($zan >= 16 && $zan < 32) {
            $starLevel  = sprintf($starIcon, 1);
            $starLevel .= sprintf($starIcon, 1);
        } else if ($zan >= 32 && $zan < 64) {
            $starLevel  = sprintf($starIcon, 1);
            $starLevel .= sprintf($starIcon, 1);
            $starLevel .= sprintf($starIcon, 1);
        } else if ($zan >= 64 && $zan < 128) {
            $starLevel  = sprintf($starIcon, 2);
            $starLevel .= sprintf($starIcon, 1);
            $starLevel .= sprintf($starIcon, 1);
        } else if ($zan >= 128 && $zan < 256) {
            $starLevel  = sprintf($starIcon, 2);
            $starLevel .= sprintf($starIcon, 2);
            $starLevel .= sprintf($starIcon, 1);
        } else if ($zan >= 256 && $zan < 512) {
            $starLevel  = sprintf($starIcon, 2);
            $starLevel .= sprintf($starIcon, 2);
            $starLevel .= sprintf($starIcon, 2);
        } else if ($zan >= 512 && $zan < 1024) {
            $starLevel  = sprintf($starIcon, 3);
            $starLevel .= sprintf($starIcon, 2);
            $starLevel .= sprintf($starIcon, 2);
        } else if ($zan >= 1024 && $zan < 2048) {
            $starLevel  = sprintf($starIcon, 3);
            $starLevel .= sprintf($starIcon, 3);
            $starLevel .= sprintf($starIcon, 2);
        } else if ($zan >= 2048 && $zan < 4096) {
            $starLevel  = sprintf($starIcon, 3);
            $starLevel .= sprintf($starIcon, 3);
            $starLevel .= sprintf($starIcon, 3);
        } else if ($zan >= 4096 && $zan < 8192) {
            $starLevel  = sprintf($starIcon, 4);
            $starLevel .= sprintf($starIcon, 3);
            $starLevel .= sprintf($starIcon, 3);
        } else if ($zan >= 8192 && $zan < 16384) {
            $starLevel  = sprintf($starIcon, 4);
            $starLevel .= sprintf($starIcon, 4);
            $starLevel .= sprintf($starIcon, 3);
        } else if ($zan >= 16384) {
            $starLevel  = sprintf($starIcon, 4);
            $starLevel .= sprintf($starIcon, 4);
            $starLevel .= sprintf($starIcon, 4);
        }

        return $starLevel;
    }

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        if (empty($this->password)) {

            // Generate a plain temporary password
            $tempPassword = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(12)));

            // Use this password as default
            $this->password = $this->getDI()
                ->getSecurity()
                ->hash($tempPassword);
        }

        // The account is not suspended by default
        $this->suspended = 'N';
    }

    // /**
    //  * Send a confirmation e-mail to the user if the account is not active
    //  */
    // public function afterSave()
    // {
    //     if ($this->active == 'N' && $this->signup_source == 'E' && $this->login == null) {
    //
    //         $emailConfirmation = new EmailConfirmations();
    //
    //         $emailConfirmation->usersId = $this->id;
    //
    //         if ($emailConfirmation->save()) {
    //
    //         }
    //     }
    // }

    /**
     * @return bool|string
     */
    public function getLastActivityTime()
    {
        return HumanTime::getHumanDayLevel($this->last_activity);
    }

    /**
     * Validate that emails are unique across users
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add('email', new Uniqueness([
            "message" => "此Email已经在本站注册过了"
        ]));

        return $this->validate($validator);
    }

    public function avatarNormal($class = 'left')
    {
        $element = '';

        if (!$this->avatar_version) {

            $element = $this->avatarLetter($class);

        } else {

            $rawId = $this->avatarRawId();

            $size = ($class == 'avatar-big') ? '120x120' : '60x60';

            $uri = substr($rawId, 0, 3).'/'.$rawId.'-'.$size.'@2x.png?v='.$this->avatar_version;

            $element = '<div class="avatar '.$class.'"><img src="/avatars/'.$uri.'"></div>';
        }

        return $element;
    }

    private function avatarLetter($class = 'left')
    {
        $firstChar = mb_substr($this->name, 0, 1);
        $firstEmailChar = mb_substr($this->login, 0, 1);

        $span = '<div class="avatar '.$class.'"><span class="text-'.strtolower($firstEmailChar).'">'.strtoupper($firstChar).'</span></div>';

        return $span;
    }

    public function avatarRawId()
    {
        // e.g., id 1001, format to 001001
        $avatarId = sprintf("%06s", $this->id);
        return $avatarId;
    }

    public function randomLetter()
    {
        $alphabet = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
                     'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

        return $alphabet[rand(0, 25)];
    }

    public function moneyRMB()
    {
        $moneyRMB = number_format($this->money/100, 2);
        return $moneyRMB;
    }
}
