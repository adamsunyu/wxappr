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

namespace Phosphorum\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Uniqueness;
use Phosphorum\Models\Users;

class VerifyForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        // Email
        $email = new Text('email', [
            'class' => 'form-control',
            'id'    => 'email',
            'placeholder' => 'Email'
        ]);

        $email->setLabel('&nbsp;');

        $email->addValidators([
            new PresenceOf([
                'message' => '你的邮箱不能为空'
            ]),
            new Email([
                'message' => '邮箱地址不正确'
            ]),
            new Uniqueness([
                'model'     => new Users(),
                'attribute' => 'email',
                'message'   => '这个Email已经在本站注册过'
            ])
        ]);

        $this->add($email);

        $submit = new Submit('发送邀请信', [
            'class' => 'btn btn-success btn-block-wx'
        ]);

        // Sign Up
        $this->add($submit);
    }
}
