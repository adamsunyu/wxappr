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
 * Class UsersCodes
 *
 * @property \Phosphorum\Models\Users user
 *
 * @package Phosphorum\Models
 */
class UsersCodes extends Model
{
    public $id;

    public $users_id;

    public $invite_code;

    public $invite_users_id;

    public $used;

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

        $this->belongsTo(
            'invite_users_id',
            'Phosphorum\Models\Users',
            'id',
            [
                'alias'    => 'invitee',
                'reusable' => true
            ]
        );

        $this->addBehavior(
            new Timestampable([
                'beforeValidationOnCreate' => ['field' => 'created_at'],
                'beforeUpdate' => ['field' => 'modified_at']
            ])
        );
    }

    public function getFormalCreatedAt()
    {
        return date('Y-m-d', $this->created_at);
    }

    static public function randomCode()
    {
        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
                    'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $rand1 = rand(0, 35);
        $rand2 = rand(0, 35);
        $rand3 = rand(0, 35);
        $rand4 = rand(0, 35);
        $rand5 = rand(0, 35);
        $rand6 = rand(0, 35);

        $code = $letters[$rand1] . $letters[$rand2] . $letters[$rand3] . $letters[$rand4] . $letters[$rand5] . $letters[$rand6];

        return $code;
    }

    static public function generateCodes($user, $count)
    {
        for ($i=0; $i < $count; $i++) {
            $newCodes = new UsersCodes();
            $newCodes->users_id = $user->id;
            $newCodes->invite_code = UsersCodes::randomCode();
            $newCodes->save();
        }
    }
}
