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
class UsersBadges extends Model
{
    public $id;

    public $users_id;

    public $badge;

    public $type;

    public $code1;

    public $code2;

    public $created_at;

    public function initialize()
    {
        $this->addBehavior(new Timestampable(['beforeValidationOnCreate' => ['field' => 'created_at']]));
    }

    public function afterCreate()
    {
    }
}
