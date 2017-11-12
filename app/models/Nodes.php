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
class Nodes extends Model
{
    public $id;

    public $parent_id;

    public $creator_id;

    public $name;

    public $slug;

    public $icon_version;

    public $about;

    public $number_posts;

    public $number_followers;

    public $created_at;

    public $modified_at;

    public $public;

    // Used for check user follow status
    public $followed;

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

    private function iconLetter($class = 'left')
    {
        $firstChar = mb_substr($this->name, 0, 1);

        $span = '<div class="avatar '.$class.'"><span class="text-node">'.strtoupper($firstChar).'</span></div>';

        return $span;
    }

    public function iconRawId()
    {
        // e.g., id 1001, format to 001001
        $avatarId = sprintf("%06s", $this->id);
        return $avatarId;
    }


    public function iconNormal($class = 'left')
    {
        $element = '';

        if (!$this->icon_version) {

            $element = $this->iconLetter($class);

        } else {

            $rawId = $this->iconRawId();

            $size = ($class == 'avatar-big') ? '120x120' : '60x60';

            $uri = substr($rawId, 0, 3).'/'.$rawId.'-'.$size.'@2x.png?v='.$this->icon_version;

            $element = '<div class="avatar '.$class.'"><img src="/icons/'.$uri.'"></div>';
        }

        return $element;
    }

    static public function reorderNodes($userNodes, $allNodes)
    {
        $newNodeList = [];

        $myNodes = [];
        $otherNodes = [];

        foreach ($allNodes as $node) {

            $isMyNode = false;

            foreach ($userNodes as $userNode) {
                if ($userNode->nodes_id == $node->id) {
                    $isMyNode = true;
                    break;
                }
            }

            if ($isMyNode) {
                $myNodes[$node->id] = $node->name;
            } else {
                $otherNodes[$node->id] = $node->name;
            }
        }

        $newNodeList = $myNodes + $otherNodes;

        return $newNodeList;
    }
}
