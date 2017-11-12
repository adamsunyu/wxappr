<?php

/*
 +------------------------------------------------------------------------+
 | wxappr.com                                                           |
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

namespace Phosphorum\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

class ChangePasswordForm extends Form
{
    public function initialize()
    {
        // Password
        $password = new Password('password', [
            'class' => 'form-control'
        ]);

        $password->addValidators([
            new PresenceOf([
                'message' => '密码不能为空'
            ]),
            new StringLength([
                'min' => 6,
                'messageMinimum' => '密码至少包含6位字符或数字'
            ]),
            new Confirmation([
                'message' => '两次输入的密码不一致',
                'with' => 'confirmPassword'
            ])
        ]);

        $this->add($password);

        // Confirm Password
        $confirmPassword = new Password('confirmPassword', [
            'class' => 'form-control'
        ]);

        $confirmPassword->addValidators([
            new PresenceOf([
                'message' => '确认密码不能为空'
            ])
        ]);

        $this->add($confirmPassword);
    }
}
