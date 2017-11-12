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

use Phalcon\Mvc\View;
use Phalcon\Http\Response;
use Phosphorum\Models\Users;
use Phosphorum\Models\Posts;
use Phosphorum\Models\PostsThanks;
use Phosphorum\Models\PostsReplies;
use Phosphorum\Models\PostsRepliesThanks;
use Phosphorum\Models\Money;
use Phosphorum\Utils\TokenTrait;
use Phosphorum\Models\ActivityNotifications;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;

/**
 * Class ThanksController
 *
 * @package Phosphorum\Controllers
 */
class ThanksController extends ControllerBase
{
    use TokenTrait;

    public function initialize()
    {
        $this->view->disable();
    }

    /**
     * Thank to a user
     *
     * @param int $id
     * @return Response
     */
    public function thankAction()
    {
        $response = new Response();

        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $error = [
                'status'  => 'error',
                'message' => '你尚未登录'
            ];
            return $response->setJsonContent($error);
        }

        $user = Users::findFirstById($usersId);
        if (!$user) {
            $userNotExist = [
                'status'  => 'error',
                'message' => '用户不存在'
            ];
            return $response->setJsonContent($userNotExist);
        }

        $toUserId = (int)$this->request->getPost('toUserId');

        $toUser = Users::findFirstById($toUserId);

        if (!$toUser) {
            $userNotExist = [
                'status'  => 'error',
                'message' => '用户不存在'
            ];
            return $response->setJsonContent($userNotExist);
        }

        if ($toUserId == $user->id) {
            $error = [
                'status'  => 'error',
                'message' => '这是你自己'
            ];
            return $response->setJsonContent($error);
        }

        $amount = (int)$this->request->getPost('amount');

        if ($amount > $user->money) {
            $error = [
                'status'  => 'error',
                'message' => '感谢金额不能超过你的总资产'
            ];
            return $response->setJsonContent($error);
        }

        $type = $this->request->getPost('thankType');

        $csrfPrefix = '';
        $csrfId = '';

        $main_id = $this->request->getPost('mainId');
        $sub_id = $this->request->getPost('subId');

        $post = null;
        $postReply = null;

        $posts_id = null;
        $replies_id = null;

        if ($type == 'P') {

            $posts_id = $main_id;
            $post = Posts::findFirstById($posts_id);

            if (!$post) {
                $contentNotExist = [
                    'status'  => 'error',
                    'message' => '主题不存在'
                ];
                return $response->setJsonContent($contentNotExist);
            }

            $csrfPrefix = 'post-';
        } else if($type == 'R') {
            $posts_id = $main_id;
            $replies_id = $sub_id;

            $postReply = PostsReplies::findFirstById($replies_id);
            if (!$postReply) {
                $contentNotExist = [
                    'status'  => 'error',
                    'message' => '回复不存在'.$replies_id
                ];
                return $response->setJsonContent($contentNotExist);
            }

            $csrfPrefix = 'post-';
        }

        if (!$this->checkTokenGetJson($csrfPrefix . $main_id)) {
            $csrfTokenError = [
                'status'  => 'error',
                'message' => '页面已失效，请刷新'
            ];
            return $response->setJsonContent($csrfTokenError);
        }

        // Thank user money
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();

        $bankbook = new UsersBankbook();
        $bankbook->setTransaction($transaction);
        $bankbook->users_id = $user->id;
        $bankbook->users_id_other = $toUserId;
        $bankbook->posts_id = $posts_id;
        $bankbook->posts_replies_id = $replies_id;
        $bankbook->balance = $user->money - $amount;
        $bankbook->type = Money::THANKS_SEND;
        $bankbook->expense = $amount;

        if (!$bankbook->save()) {
            $transaction->rollback("Can't transfer money");
            $error = [
                'status'  => 'error',
                'message' => '转账失败，请重试'
            ];
            return $response->setJsonContent($error);
        }

        $tranferError = [
            'status'  => 'error',
            'message' => '转账失败，请重试'
        ];

        $bankbook2 = new UsersBankbook();
        $bankbook2->setTransaction($transaction);
        $bankbook2->users_id = $toUserId;
        $bankbook2->users_id_other = $user->id;
        $bankbook2->posts_id = $posts_id;
        $bankbook2->posts_replies_id = $replies_id;
        $bankbook2->balance = $toUser->money + $amount;
        $bankbook2->type = Money::THANKS_GET;
        $bankbook2->income = $amount;

        if (!$bankbook2->save()) {
            $transaction->rollback("Can't transfer money");
            return $response->setJsonContent($tranferError);
        }

        $user->setTransaction($transaction);
        $user->money -= $amount;
        $user->thanks_send += 1;
        if (!$user->save()) {
            $transaction->rollback("Can't transfer money");
            return $response->setJsonContent($tranferError);
        }

        $toUser->setTransaction($transaction);
        $toUser->money += $amount;
        $toUser->thanks_receive += 1;
        if (!$toUser->save()) {
            $transaction->rollback("Can't transfer money");
            return $response->setJsonContent($tranferError);
        }

        $transaction->commit();

        if ($type == 'P') {
            $post->number_thanks += 1;
            $post->save();
        } else if($type == 'R') {
            $postReply->number_thanks += 1;
            $postReply->save();
        }

        $activity                       = new ActivityNotifications();
        $activity->users_id             = $toUser->id;
        $activity->posts_id             = $posts_id;
        $activity->posts_replies_id     = $replies_id;
        $activity->users_origin_id      = $user->id;
        $activity->extra                = $amount;
        $activity->type                 = Money::THANKS_GET;
        $activity->save();

        $this->flashSession->success('感谢成功');

        return $response->setJsonContent(['status' => 'OK']);
    }
}
