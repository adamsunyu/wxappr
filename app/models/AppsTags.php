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
 * Phosphorum\Models\Images
 */
class AppsTags extends Model
{
    public $id;

    public $name;

    public $slug;

    public $number_apps;

    public $rank;

    public $created_at;

    public $modified_at;

    public function initialize()
    {
        $this->addBehavior(
            new Timestampable(
                [
                    'beforeCreate' => [
                        'field' => ['created_at', 'modified_at'],
                    ]
                ]
            )
        );
    }

    public static function getOrCreateTag($tagName)
    {
        $tagName = trim($tagName);

        $appTag = AppsTags::findFirstByName($tagName);

        if ($appTag == null) {
            $appTag = new AppsTags();
            $appTag->name = $tagName;
            $appTag->slug = $tagName;
            $appTag->rank = 9;
            $appTag->number_apps = 1;
        } else {
            $appTag->number_apps += 1;
        }

        $appTag->save();

        return $appTag;
    }
}
