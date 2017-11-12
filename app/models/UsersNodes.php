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
 * Class UserNodes
 *
 * @property \Phosphorum\Models\Posts post
 * @property \Phosphorum\Models\Users user
 *
 * @package Phosphorum\Models
 */
class UsersNodes extends Model
{
    public $id;

    public $users_id;

    public $nodes_id;

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
            'nodes_id',
            'Phosphorum\Models\Nodes',
            'id',
            [
                'alias'    => 'node',
                'reusable' => true
            ]
        );

        $this->addBehavior(
            new Timestampable([
                'beforeValidationOnCreate' => ['field' => 'created_at']
            ])
        );
    }

    public static function checkFollow($nodes_followed, $node_id)
    {
        $followed = false;

        foreach ($nodes_followed as $userNode) {
            if ($userNode->nodes_id == $node_id) {
                $followed = true;
                break;
            }
        }

        return $followed;
    }
}
