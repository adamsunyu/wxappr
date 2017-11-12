<?php

namespace Phosphorum\Utils;

/**
 * HumanTime
 *
 * Transforms to a human readable time string.
 */
class HumanTime
{
    public static function diffDays()
    {
        $dateStr = date("Y-m-d H:i:s", $change->modifiedAt);
        $date = new \DateTime($dateStr);
        $now = new \DateTime('now');
        $interval = $now->diff($date);

        return $interval->d;
    }
    /**
     * Get a human readable time year level
     *
     * @param  int $time time to trans
     * @return string
     */
    public static function getHumanYearLevel($time)
    {
        $diff = time() - $time;

        if ($diff > (86400 * 30 * 12 * 10)) {
            return date('Y-m-d', $time);
        }

        if ($diff > 86400 * 30 * 12) {
            return ((int)($diff / 31104000)) . '年前';
        }

        if ($diff > (86400 * 30)) {
            return ((int)($diff / 2592000)) . '月前';
        }

        if ($diff > 86400) {
            return ((int)($diff / 86400)) . '天前';
        }

        if ($diff > 3600) {
            return ((int)($diff / 3600)) . '小时前';
        }

        if ($diff > 60) {
            return ((int)($diff / 60)) . '分钟前';
        }

        return '刚刚';
    }

    /**
     * Get a human readable time day level
     *
     * @param  int $time time to trans
     * @return string
     */
    public static function getHumanDayLevel($time)
    {
        $diff = time() - $time;

        if ($diff > (86400 * 30)) {
            return date('Y-m-d H:i', $time);
        }

        if ($diff > 86400) {
            return ((int)($diff / 86400)) . '天前';
        }

        if ($diff > 3600) {
            return ((int)($diff / 3600)) . '小时前';
        }

        if ($diff > 60) {
            return ((int)($diff / 60)) . '分钟前';
        }

        return '刚刚';
    }
}
