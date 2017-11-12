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
use Phosphorum\Models\Apps;

class AppEditForm extends Form
{
    public function initialize()
    {
        $name = new Text('name', [
            'class' => 'form-control',
            'placeholder' => '请输入小程序名字'
        ]);

        $name->setLabel('小程序名称');

        $name->addValidators([
            new PresenceOf([
                'message' => '名称不能为空'
            ]),
            new StringLength([
                'min' => 2,
                'max' => 20,
                'messageMinimum' => '名称不能少于2个字符',
                'messageMaximum' => '名称不能多于20个字符'
            ])
        ]);

        $this->add($name);

        $tags = new Text('appTags', [
            'class' => 'form-control',
            'data-role' => 'tagsinput',
            'style' => 'width: 100%;',
            'placeholder' => ''
        ]);

        $tags->setLabel('小程序标签');

        $tags->addValidators([
            new PresenceOf([
                'message' => '标签不能为空'
            ])
        ]);

        $this->add($tags);
    }
}
