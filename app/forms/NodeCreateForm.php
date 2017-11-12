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
use Phosphorum\Models\Nodes;

class NodeCreateForm extends Form
{
    public function initialize()
    {
        $name = new Text('name', [
            'class' => 'form-control',
            'placeholder' => '不能超过12个字符'
        ]);

        $name->setLabel('名称:');

        $name->addValidators([
            new PresenceOf([
                'message' => '名称不能为空'
            ]),
            new StringLength([
                'min' => 2,
                'max' => 20,
                'messageMinimum' => '名称不能少于2个字符',
                'messageMaximum' => '名称不能多于20个字符'
            ]),
            new Uniqueness([
                'model' => new Nodes(),
                'attribute' => 'name',
                'message' => '此名称已经存在'
            ])
        ]);

        $this->add($name);

        $domain = new Text('slug', [
            'class' => 'form-control',
            'placeholder' => '只能使用英语字母，数字或-'
        ]);

        $domain->setLabel('域名:');

        $domain->addValidators([
            new PresenceOf([
                'message' => '域名不能为空'
            ]),
            new StringLength([
                'min' => 2,
                'max' => 20,
                'messageMinimum' => '域名不能少于2个字符',
                'messageMaximum' => '域名不能多于20个字符'
            ]),
            new Uniqueness([
                'model' => new Nodes(),
                'attribute' => 'slug',
                'message' => '此域名已经存在'
            ])
        ]);

        $this->add($domain);
    }
}
