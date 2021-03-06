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

/**
 * ChangesLogin
 * Register when a user changes his/her login
 */
class ChangesLogin extends Model
{
    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $usersId;

    /**
     *
     * @var string
     */
    public $ipAddress;

    /**
     *
     * @var string
     */
    public $userAgent;

    /**
     *
     * @var integer
     */
    public $createdAt;

    public $modifiedAt;

    /**
     * Before change the name
     */
    public function beforeValidationOnCreate()
    {
        // Timestamp the confirmaton
        $this->createdAt = time();
    }

    /**
     * Sets the timestamp before update the name
     */
    public function beforeValidationOnUpdate()
    {
        $this->modifiedAt = time();
    }

    public function initialize()
    {
        $this->belongsTo(
            'usersId',
             'Phosphorum\Models\Users',
             'id', [
                 'alias' => 'user'
             ]
         );
    }
}
