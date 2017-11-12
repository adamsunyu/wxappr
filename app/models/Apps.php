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
use Phosphorum\Utils\HumanTime;
use Phalcon\Mvc\Model\Behavior\Timestampable;

/**
 * Phosphorum\Models\Images
 */
class Apps extends Model
{
    // New app
    const APP_STATUS_NEW      = 'N';

    const APP_STATUS_ICON     = 'I';

    // Ready for publish
    const APP_STATUS_READY    = 'R';

    // Public
    const APP_STATUS_PUBLIC   = 'P';

    public $id;

    public $creator_id;

    public $name;

    public $slug;

    public $desc;

    public $tag1_id;

    public $tag2_id;

    public $tag3_id;

    public $icon_version;

    public $qrcode_version;

    public $screen1_version;

    public $screen2_version;

    public $screen3_version;

    public $screen4_version;

    public $screen5_version;

    public $votes_up;

    public $votes_down;

    public $number_reviews;

    public $number_views;

    public $number_favorites;

    public $status;

    public $created_at;

    public $modified_at;

    public function initialize()
    {
        $this->belongsTo(
            'tag1_id',
            'Phosphorum\Models\AppsTags',
            'id',
            [
                'alias'    => 'tag1',
                'reusable' => true
            ]
        );

        $this->belongsTo(
            'tag2_id',
            'Phosphorum\Models\AppsTags',
            'id',
            [
                'alias'    => 'tag2',
                'reusable' => true
            ]
        );

        $this->belongsTo(
            'tag3_id',
            'Phosphorum\Models\AppsTags',
            'id',
            [
                'alias'    => 'tag3',
                'reusable' => true
            ]
        );

        $this->belongsTo(
            'creator_id',
            'Phosphorum\Models\Users',
            'id',
            [
                'alias'    => 'creator',
                'reusable' => true
            ]
        );

        $this->addBehavior(
            new Timestampable(
                [
                    'beforeCreate' => [
                        'field' => ['created_at', 'modified_at']
                    ],
                    'beforeUpdate' => ['field' => 'modified_at']
                ]
            )
        );
    }

    public function imageFolder()
    {
        $rawId = $this->iconRawId();

        $folder = BASE_DIR . 'public/appdata/'.substr($rawId, 0, 3).'/'.$rawId;

        return $folder;
    }

    public function iconURI()
    {
        $rawId = $this->iconRawId();

        $uri = '/appdata/'.substr($rawId, 0, 3).'/'.$rawId.'/icon.png?v='.$this->icon_version;

        return $uri;
    }

    public function iconSmallURI()
    {
        $rawId = $this->iconRawId();

        $uri = '/appdata/'.substr($rawId, 0, 3).'/'.$rawId.'/icon-small.png?v='.$this->icon_version;

        return $uri;
    }

    public function qrcodeURI()
    {
        $rawId = $this->iconRawId();

        $uri = '/appdata/'.substr($rawId, 0, 3).'/'.$rawId.'/qrcode.png?v='.$this->qrcode_version;

        return $uri;
    }

    public function screenshotURI($id)
    {
        $rawId = $this->iconRawId();

        $screenImage = '';
        if ($id == 1) {
            $screenImage = 'screenshot1.png?v='.$this->screen1_version;
        } else if($id == 2) {
            $screenImage = 'screenshot2.png?v='.$this->screen2_version;
        } else if($id == 3) {
            $screenImage = 'screenshot3.png?v='.$this->screen3_version;
        } else if($id == 4) {
            $screenImage = 'screenshot4.png?v='.$this->screen4_version;
        }

        $uri = '/appdata/'.substr($rawId, 0, 3).'/'.$rawId.'/'.$screenImage;

        return $uri;
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

            $firstChar = mb_substr($this->name, 0, 1);

            $element = '<div class="avatar '.$class.'"><span class="text-node">'.strtoupper($firstChar).'</span></div>';

        } else {

            $rawId = $this->iconRawId();

            $uri = $this->iconURI();

            $element = '<div class="avatar '.$class.'"><img src="'.$uri.'"></div>';
        }

        return $element;
    }

    public function iconSmall($class = 'left')
    {
        $element = '';

        if (!$this->icon_version) {

            $firstChar = mb_substr($this->name, 0, 1);

            $element = '<div class="avatar '.$class.'"><span class="text-node">'.strtoupper($firstChar).'</span></div>';

        } else {

            $rawId = $this->iconRawId();

            $uri = $this->iconSmallURI();

            $element = '<div class="avatar '.$class.'"><img src="'.$uri.'"></div>';
        }

        return $element;
    }

    /**
     * @return bool|string
     */
    public function getHumanCreatedAt()
    {
        return HumanTime::getHumanYearLevel($this->created_at);
    }

    public function getHumanModifiedAt()
    {
        return HumanTime::getHumanYearLevel($this->modified_at);
    }
}
