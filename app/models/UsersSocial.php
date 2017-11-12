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
 * Class UsersBadges
 *
 * @package Phosphorum\Models
 */
class UsersSocial extends Model
{
    public $id;

    public $users_id;

    public $gender;
    
    public $skills;

    public $github;

    public $weibo;

    public $website;

    public $gzhao;

    public $zhihu;

    public $modified_at;

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

        $this->addBehavior(
            new Timestampable([
                'beforeCreate' => ['field' => 'created_at'],
                'beforeUpdate' => ['field' => 'modified_at']
            ])
        );
    }

    public function genderText()
    {
        $text = '';

        if ($this->gender == null) {
            $text = '未知';
        } else if ($this->gender == 'M') {
            $text = '男';
        } else if ($this->gender == 'W') {
            $text = '女';
        } else if ($this->gender == 'O') {
            $text = '不公开';
        }

        return $text;
    }

    public static function getInfoById($usersId)
    {
        $socialInfo = UsersSocial::findFirstByUsersId($usersId);

        if ($socialInfo != null) {
            $gender  = $socialInfo->genderText();
            $city    = $socialInfo->city ?: '未知';
            $skills  = $socialInfo->skills ?: '未知';
            $github  = $socialInfo->github ?: '未知';
            $weibo   = $socialInfo->weibo ?: '未知';
            $website = $socialInfo->website ?: '未知';
            $gzhao   = $socialInfo->gzhao ?: '未知';
            $zhihu   = $socialInfo->zhihu ?: '未知';
        } else {
            $gender  = $city = $skills = $github = $weibo = $website = $gzhao = $zhihu = '未知';
        }

        $showSocialSidebar = false;
        if (($gender != '未知' && $gender != '不公开') || $city != '未知' || $skills != '未知' || $github != '未知' ||
            $weibo != '未知' || $website != '未知' || $gzhao != '未知' || $zhihu != '未知') {
            $showSocialSidebar = true;
        }

        $socialData = [
                        'gender'  => $gender,
                        'city'    => $city,
                        'skills'  => $skills,
                        'github'  => $github,
                        'weibo'   => $weibo,
                        'website' => $website,
                        'gzhao'   => $gzhao,
                        'zhihu'   => $zhihu
                      ];

        $socialInfo = [$showSocialSidebar, $socialData];

        return $socialInfo;
    }
}
