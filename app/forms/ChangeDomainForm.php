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
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Uniqueness;
use Phosphorum\Models\Users;

class ChangeDomainForm extends Form
{
    public function initialize()
    {
        $name = new Text('login', [
            'class' => 'form-control'
        ]);

        $name->setLabel('新的域名:');

        $name->addValidators([
            new PresenceOf([
                'message' => '域名不能为空'
            ]),
            new StringLength([
                'min' => 4,
                'max' => 12,
                'messageMinimum' => '域名长度不能少于4个字符',
                'messageMaximum' => '域名长度不能多于12个字符'
            ]),
            new Uniqueness([
                'model' => new Users(),
                'attribute' => 'login',
                'message' => '此域名已经被人占用'
            ])
        ]);

        $this->add($name);

        $save = new Submit('保存', [
            'class' => 'btn btn-success'
        ]);

        $this->add($save);
    }
}
