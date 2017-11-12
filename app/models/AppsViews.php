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
 * Class AppsViews
 *
 * @property \Phosphorum\Models\Apps app
 *
 * @package Phosphorum\Models
 */
class AppsViews extends Model
{

    public $id;

    public $apps_id;

    public $ipaddress;

    public function initialize()
    {
        $this->belongsTo(
            'apps_id',
            'Phosphorum\Models\Apps',
            'id',
            [
                'alias' => 'app'
            ]
        );
    }

    public function clearCache()
    {
        if ($this->id) {
            $viewCache = $this->getDI()->getShared('viewCache');
            $viewCache->delete('app-' . $this->apps_id);
        }
    }
}
