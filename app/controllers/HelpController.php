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

namespace Phosphorum\Controllers;

use Phalcon\Http\Response;
use Phosphorum\Models\Posts;
use Phosphorum\Models\Users;
use Phosphorum\Models\Nodes;

/**
 * Class HelpController
 *
 * @package Phosphorum\Controllers
 */
class HelpController extends ControllerBase
{
    public function economicsAction()
    {
        $this->tag->setTitle("经济系统");
    }

    public function levelAction()
    {
        $this->tag->setTitle("会员等级");
    }

    public function markdownAction()
    {
        $this->tag->setTitle("Markdown帮助");
    }

    public function votingAction()
    {
        $this->tag->setTitle("回复和点赞");
    }

    public function violationAction()
    {
        $this->tag->setTitle("删帖原则");
    }

    public function aboutAction()
    {
        $this->tag->setTitle("关于社区");
    }

    public function agreementAction()
    {
        $this->tag->setTitle("用户协议");
    }

    public function guidelineAction()
    {
        $this->tag->setTitle("社区原则");
    }

    public function disclaimerAction()
    {
        $this->tag->setTitle("免责声明");
    }

    public function privacyAction()
    {
        $this->tag->setTitle("隐私声明");
    }

    public function colorAction()
    {
        $this->tag->setTitle("头像颜色表");
    }

    public function volunteersAction()
    {
        $this->tag->setTitle("志愿者招募");
    }

    public function feedbacksAction()
    {
        $this->tag->setTitle('社区反馈');
    }

    public function invitationAction()
    {
        $this->tag->setTitle('邀请码');
    }

    public function wechatAction()
    {
        $this->tag->setTitle('微信群');
    }

    public function statsAction()
    {
        if (!$usersId = $this->session->get('identity')) {
            $this->response->redirect('/account/login');
            return;
        }

        $this->tag->setTitle("社区运行状况");

        $this->view->setVars([
            'threads'       => Posts::count(),
            'nodes'         => Nodes::count(),
            'replies'       => Posts::sum(['column' => 'number_replies']),
            'votes'         => Users::sum(['column' => 'votes_send']),
            'users'         => Users::count(),
            'money'         => Users::sum(['column' => 'money'])
        ]);
    }
}
