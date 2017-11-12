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

class SignUpForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        $name = new Text('name', [
            'class' => 'form-control',
            'id'    => 'nameInput',
            'placeholder' => '你的姓名'
        ]);

        $name->setLabel('&nbsp;');

        $name->addValidators([
            new PresenceOf([
                'message' => '姓名不能为空'
            ]),
            new StringLength([
                'min' => 2,
                'max' => 16,
                'messageMinimum' => '姓名长度不能少于2个字符',
                'messageMaximum' => '姓名长度不能多于16个字符'
            ]),
            new Uniqueness([
                'model' => new Users(),
                'attribute' => 'name',
                'message' => '此名称已经被人占用'
            ])
        ]);

        $this->add($name);

        // Email
        $city = new Text('city', [
            'class' => 'form-control',
            'id'    => 'cityInput',
            'placeholder' => '居住城市'
        ]);

        $city->setLabel('&nbsp;');

        $city->addValidators([
            new PresenceOf([
                'message' => '居住城市不能为空'
            ])
        ]);

        $this->add($city);

        // Password
        $password = new Password('password', [
            'class' => 'form-control',
            'id'    => 'passwordInput',
            'placeholder' => '密码'
        ]);

        $password->setLabel('&nbsp;');

        $password->addValidators([
            new PresenceOf([
                'message' => '密码不能为空'
            ]),
            new StringLength([
                'min' => 6,
                'messageMinimum' => '密码至少6个字符或数字'
            ]),
            new Confirmation([
                'message' => '两次密码不一致',
                'with' => 'confirmPassword'
            ])
        ]);

        $this->add($password);

        // Confirm Password
        $confirmPassword = new Password('confirmPassword', [
            'class' => 'form-control',
            'id'    => 'confirumPasswordInput',
            'placeholder' => '确认密码'
        ]);

        $confirmPassword->setLabel('&nbsp;');

        $confirmPassword->addValidators([
            new PresenceOf([
                'message' => '确认密码不能为空'
            ])
        ]);

        $this->add($confirmPassword);

        //
        // $terms = new Check('terms', [
        //     'value' => 'yes'
        // ]);
        //
        // $terms->setLabel('我已阅读并同意本站的<a href="/about/agreement">《使用协议》</a>');
        //
        // $terms->addValidator(new Identical([
        //     'value' => 'yes',
        //     'message' => '请同意本站的《使用协议》'
        // ]));
        //
        // $this->add($terms);

        // CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(new Identical([
            'value' => $this->security->getSessionToken(),
            'message' => 'CSRF validation failed'
        ]));

        $csrf->clear();

        $this->add($csrf);

        $submit = new Submit('完成注册', [
            'class' => 'btn btn-success btn-block-wx'
        ]);

        // Sign Up
        $this->add($submit);
    }

    /**
     * Prints messages for a specific element
     */
    public function messages($name)
    {
        if ($this->hasMessagesFor($name)) {
            foreach ($this->getMessagesFor($name) as $message) {
                $this->flash->error($message);
            }
        }
    }
}
