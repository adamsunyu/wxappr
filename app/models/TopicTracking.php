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
 * Class TopicTracking
 *
 * @property \Phosphorum\Models\Posts post
 * @method static TopicTracking[] find($parameters=null)
 * @method static TopicTracking findFirst($parameters=null)
 *
 * @package Phosphorum\Models
 */
class TopicTracking extends Model
{
    public $id;

    public $topic_id;

    public $user_id;

    public function initialize()
    {
        $this->setSource('topic_tracking');
    }

    public function updateTracking($postId, $userId)
    {
        $sql = "
            UPDATE topic_tracking
            SET topic_id=IF(topic_id='',{$postId}, CONCAT(topic_id,',{$postId}'))
            WHERE user_id=:user_id
            AND NOT (FIND_IN_SET('{$postId}', topic_id) OR FIND_IN_SET(' {$postId}', topic_id))
        ";

        return $this->getReadConnection()->query($sql, ['user_id' => $userId]);
    }
}
