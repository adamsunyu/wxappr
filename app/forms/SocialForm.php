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
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

class SocialForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
        } else {
            $id = new Text('id');
        }

        $this->add($id);

        // 性别
        $gender = new Select('gender', [
            'M' => '男',
            'W' => '女',
            'O' => '不公开'
        ], [
            'class' => 'form-control',
            'id'    => 'setting-value-a'
        ]);

        $this->add($gender);

        // 城市
        $city = new Text('city', [
            'class' => 'form-control',
            'id'    => 'setting-value-b',
            'placeholder' => ''
        ]);

        $city->setLabel('&nbsp;');

        $this->add($city);

        // 专长
        $skills = new Text('skills', [
            'class' => 'form-control',
            'id'    => 'setting-value-c',
            'placeholder' => ''
        ]);
        $city->setLabel('&nbsp;');
        $this->add($skills);

        // Github
        $github = new Text('github', [
            'class' => 'form-control',
            'id'    => 'setting-value-d',
            'placeholder' => ''
        ]);
        $city->setLabel('&nbsp;');
        $this->add($github);

        // weibo
        $weibo = new Text('weibo', [
            'class' => 'form-control',
            'id'    => 'setting-value-e',
            'placeholder' => ''
        ]);
        $city->setLabel('&nbsp;');
        $this->add($weibo);

        // website
        $website = new Text('website', [
            'class' => 'form-control',
            'id'    => 'setting-value-f',
            'placeholder' => ''
        ]);
        $city->setLabel('&nbsp;');
        $this->add($website);

        // 公众号
        $gzhao = new Text('gzhao', [
            'class' => 'form-control',
            'id'    => 'setting-value-g',
            'placeholder' => ''
        ]);
        $city->setLabel('&nbsp;');
        $this->add($gzhao);

        $save = new Submit('确定', [
            'class' => 'btn btn-success setting-social-btn'
        ]);

        // 知乎
        $zhihu = new Text('zhihu', [
            'class' => 'form-control',
            'id'    => 'setting-value-h',
            'placeholder' => ''
        ]);
        $city->setLabel('&nbsp;');
        $this->add($zhihu);

        $save = new Submit('确定', [
            'class' => 'btn btn-success setting-social-btn'
        ]);

        $this->add($save);
    }
}
