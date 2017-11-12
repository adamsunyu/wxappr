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
 * Phosphorum\Models\Profiles
 * All the profile levels in the application. Used in conjenction with ACL lists
 */
class Roles extends Model
{

    /**
     * ID
     * @var integer
     */
    public $id;

    /**
     * Name
     * @var string
     */
    public $name;

    /**
     * Define relationships to Users and Permissions
     */
    public function initialize()
    {
        $this->hasMany('id', __NAMESPACE__ . '\Users', 'role_id', [
            'alias' => 'users',
            'foreignKey' => [
                'message' => 'Roles cannot be deleted because it\'s used on Users'
            ]
        ]);

        $this->hasMany('id', __NAMESPACE__ . '\Permissions', 'role_id', [
            'alias' => 'permissions'
        ]);
    }
}
