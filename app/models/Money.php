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

/**
 * Money constants
 */
abstract class Money
{
    // The type of the money
    const INITIAL_INCOME      = 'II';
    const NUM_INITIAL_INCOME  = 100;

    const DAILY_INCOME        = 'DI';
    const NUM_DAILY_INCOME    = 15;

    const POST_NEW            = 'PN';
    const NUM_POST_NEW        = 10;

    const POST_STICKY         = 'PS';
    const NUM_POST_STICKY     = 50;

    const SELF_STICKY         = 'SS';

    const POST_REPLY          = 'PR';
    const NUM_POST_REPLY      = 3;

    const DELETE_POST         = 'DP';
    const NUM_DELETE_POST     = 15;

    const DELETE_REPLY        = 'DR';
    const NUM_DELETE_REPLY    = 5;

    const MODERATE_DELETE_POST = 'MP';
    const NUM_MODERATE_DELETE_POST = 20;

    const MODERATE_DELETE_REPLY = 'MR';
    const NUM_MODERATE_DELETE_REPLY = 10;

    const THANKS_SEND        = 'TS';
    const THANKS_GET         = 'TG';
}
