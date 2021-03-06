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

namespace Phosphorum\Badges\Badge;

use Phosphorum\Models\Users;
use Phosphorum\Models\UsersBadges;
use Phosphorum\Models\Categories;
use Phosphorum\Badges\BadgeBase;

/**
 * Phosphorum\Badges\Badge\Virtuoso
 *
 * More than 5 accepted answers in specific categories
 */
class Virtuoso extends BadgeBase
{
    protected $name = 'Virtuoso';

    protected $description = 'More than 5 accepted answers in specific categories';

    protected $query;

    public function getExpertQuery(Users $user)
    {
        if (!$this->query) {
            $this->query = $user->getModelsManager()->createBuilder()
                ->columns(['p.categories_id', 'COUNT(*)'])
                ->from(['r' => 'Phosphorum\Models\PostsReplies'])
                ->join('Phosphorum\Models\Posts', null, 'p')
                ->where('r.users_id = ?0 AND r.accepted = "Y"')
                ->notInWhere('p.categories_id', $this->getNoBountyCategories())
                ->groupBy('p.categories_id')
                ->having('COUNT(*) >= 5')
                ->getQuery();
        }
        return $this->query;
    }

    /**
     * Check whether the user already have this badge
     *
     * @param Users $user
     * @return boolean
     */
    public function has(Users $user)
    {
        $has = false;
        $categories = $this->getExpertQuery($user)->execute([$user->id]);
        foreach ($categories as $categoryRow) {
            $category = Categories::findFirstById($categoryRow->categories_id);
            if ($category) {
                $badgeName = $category->name . ' / ' . $this->getName();
                $has |= (UsersBadges::count([
                    'users_id = ?0 AND badge = ?1',
                    'bind' => [$user->id, $badgeName]
                ]) == 0);
            }
        }
        return (boolean) !$has;
    }

    /**
     * Check whether the user can have the badge
     *
     * @param  Users $user
     * @return boolean
     */
    public function canHave(Users $user)
    {
        $ids = [];
        $categories = $this->getExpertQuery($user)->execute([$user->id]);
        foreach ($categories as $categoryRow) {
            $category = Categories::findFirstById($categoryRow->categories_id);
            if ($category) {
                $ids[] = $category;
            }
        }
        return $ids;
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
        $name = $this->getName();
        foreach ($extra as $category) {
            $userBadge = new UsersBadges();
            $userBadge->users_id = $user->id;
            $userBadge->badge    = $category->name . ' / ' . $name;
            $userBadge->save();
        }

        return $this;
    }
}
