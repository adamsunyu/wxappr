<?php

/*
 +------------------------------------------------------------------------+
 | Phosphorum                                                             |
 +------------------------------------------------------------------------+
 | Copyright (c) 2013-2016 Phalcon Team and contributors                  |
 +------------------------------------------------------------------------+
 | This source file is subject to the New BSD License that is bundled     |
 | with this package in the file docs/LICENSE.txt.                        |
 |                                                                        |
 | If you did not receive a copy of the license and are unable to         |
 | obtain it through the world-wide-web, please send an email             |
 | to admin@wxappr.com so we can send you a copy immediately.             |
 +------------------------------------------------------------------------+
*/

namespace Phosphorum\Badges;

use Phosphorum\Models\Users;
use Phosphorum\Models\UsersBadges;
use Phosphorum\Models\Categories;

/**
 * Phosphorum\Badges\Manager
 *
 * @property  string name
 * @property  string description
 */
abstract class BadgeBase implements BadgeInterface
{
    protected $noBountyCategories;

    protected $fullNoBountyCategories;

    /**
     * Check whether the user already have this badge
     *
     * @param Users $user
     * @return boolean
     */
    public function has(Users $user)
    {
        return UsersBadges::count([
            'users_id = ?0 AND badge = ?1',
            'bind' => [$user->id, $this->getName()]
        ]) > 0;
    }

    /**
     * Returns the name of the badge
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the description of the badge
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add the badge to the user
     *
     * @param Users $user
     * @param array $extra
     * @return $this
     */
    public function add(Users $user, $extra = null)
    {
        $userBadge = new UsersBadges();
        $userBadge->users_id = $user->id;
        $userBadge->badge = $this->getName();
        $userBadge->save();

        return $this;
    }
}
