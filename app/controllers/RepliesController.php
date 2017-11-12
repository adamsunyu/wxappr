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
use Phosphorum\Models\Bank;
use Phosphorum\Models\PostsReplies;
use Phosphorum\Models\PostsRepliesVotes;
use Phosphorum\Utils\TokenTrait;
use Phosphorum\Models\ActivityNotifications;

/**
 * Class RepliesController
 *
 * @package Phosphorum\Controllers
 */
class RepliesController extends ControllerBase
{
    use TokenTrait;

    public function initialize()
    {
        $this->view->disable();
    }

    /**
     * Returns the raw comment as it as edited
     *
     * @param $id
     * @return Response
     */
    public function getAction($id)
    {
        $response = new Response();

        $usersId = $this->session->get('identity');
        if (!$usersId) {
            $response->setStatusCode(401, 'Unauthorized');
            return $response;
        }

        $parametersReply = [
            'id = ?0',
            'bind' => [$id]
        ];
        $postReply = PostsReplies::findFirst($parametersReply);
        if ($postReply) {
            $data = ['status' => 'OK', 'id' => $postReply->id, 'comment' => $postReply->content];
        } else {
            $data = ['status' => 'ERROR'];
        }

        $response->setJsonContent($data);
        return $response;
    }

    /**
     * Updates a reply
     */
    public function updateAction()
    {
        $usersId = $this->session->get('identity');
        if (!$usersId) {
            return $this->response->redirect();
        }

        if (!$this->request->isPost()) {
            return $this->response->redirect();
        }

        $parametersReply = [
            'id = ?0 AND (users_id = ?1 OR "Y" = ?2)',
            'bind' => [
                $this->request->getPost('id'),
                $usersId,
                $this->session->get('identity-moderator')
            ]
        ];
        $postReply = PostsReplies::findFirst($parametersReply);
        if (!$postReply) {
            return $this->response->redirect();
        }

        if (!$this->checkTokenPost('post-' . $postReply->post->id)) {
            $this->flashSession->error('页面已过期，请重试');
            return $this->response->redirect();
        }

        $content = $this->request->getPost('content');
        if (trim($content)) {
            $postReply->content   = $content;
            $postReply->edited_at = time();
            if ($postReply->save()) {
                if ($usersId != $postReply->users_id) {
                    $user = Users::findFirstById($usersId);
                    if ($user) {
                        if ($user->moderator == 'Y') {
                            $user->save();
                        }
                    }
                }
            }
        }

        $href = 'topic/' . $postReply->post->id . '#C' . $postReply->id;
        return $this->response->redirect($href);
    }

    /**
     * Deletes a reply
     *
     * @param int $id
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $this->flashSession->error('你尚未登录');
            return $this->response->redirect();
        }

        if (!$user = Users::findFirstById($usersId)) {
            $this->flashSession->error('你尚未登录');
            $this->response->redirect();
            return;
        }

        $is_moderator = $this->session->get('identity-moderator');

        $parametersReply = [
            'id = ?0 AND (users_id = ?1 OR "Y" = ?2)',
            'bind' => [$id, $usersId, $is_moderator]
        ];

        $postReply = PostsReplies::findFirst($parametersReply);

        if (!$postReply) {
            $this->flashSession->error('Post reply does not exist');
            return $this->response->redirect();
        }

        if (!$this->checkTokenGetJson('post-' . $postReply->post->id)) {
            $this->flashSession->error('页面已失效，请重新提交');
            return $this->response->redirect('topic/' . $postReply->post->id);
        }

        if ($postReply) {

            if ($postReply->delete()) {

                $postReply->post->number_replies--;
                $postReply->post->save();

                if ($is_moderator) {
                    Bank::handleModerateReply($postReply->user, $postReply);
                } else {
                    Bank::handleDeleteReply($postReply->user);
                }

                $this->flashSession->success('删除成功');
            }

            $href = 'topic/' . $postReply->post->id;
            return $this->response->redirect($href);
        }

        return $this->response->redirect();
    }

    /**
     * Votes a post up
     *
     * @param int $id
     * @return Response
     */
    public function voteUpAction($id = 0)
    {
        $response = new Response();

        /**
         * Find the post using get
         */
        $postReply = PostsReplies::findFirstById($id);
        if (!$postReply) {
            $contentNotExist = [
                'status'  => 'error',
                'message' => '主题不存在'
            ];
            return $response->setJsonContent($contentNotExist);
        }

        $currentUserId = $this->session->get('identity');

        if ($postReply->users_id == $currentUserId) {
            $voteSelfError = [
                'status'  => 'error',
                'message' => '这是你自己的回复'
            ];
            return $response->setJsonContent($voteSelfError);
        }

        $user = Users::findFirstById($this->session->get('identity'));
        if (!$user) {
            $contentLogIn = [
                'status'  => 'error',
                'message' => '你尚未登录'
            ];
            return $response->setJsonContent($contentLogIn);
        }

        $post = $postReply->post;
        if (!$post) {
            $contentPostNotExist = [
                'status'  => 'error',
                'message' => '所回复主题不存在'
            ];
            return $response->setJsonContent($contentPostNotExist);
        }

        if ($post->deleted) {
            $contentDeleted = [
                'status'  => 'error',
                'message' => '所回复主题已被删除'
            ];
            return $response->setJsonContent($contentDeleted);
        }

        $parametersVoted = [
            'posts_replies_id = ?0 AND users_id = ?1',
            'bind' => [$postReply->id, $user->id]
        ];
        $voted = PostsRepliesVotes::count($parametersVoted);
        if ($voted) {
            $contentAlreadyVoted = [
                'status'  => 'error',
                'message' => '你已经赞过此回复'
            ];
            return $response->setJsonContent($contentAlreadyVoted);
        }

        if (!$this->checkTokenGetJson('post-' . $postReply->post->id)) {
            $csrfTokenError = [
                'status'  => 'error',
                'message' => '页面已失效，请刷新'
            ];
            return $response->setJsonContent($csrfTokenError);
        }

        $postReplyVote                   = new PostsRepliesVotes();
        $postReplyVote->posts_replies_id = $postReply->id;
        $postReplyVote->users_id         = $user->id;
        $postReplyVote->vote             = PostsRepliesVotes::VOTE_UP;

        if (!$postReplyVote->save()) {
            foreach ($postReplyVote->getMessages() as $message) {
                $contentError = [
                    'status'  => 'error',
                    'message' => $message->getMessage()
                ];
                return $response->setJsonContent($contentError);
            }
        }

        $postReply->votes_up++;

        if ($postReply->users_id != $user->id) {
            $postReply->user->votes_receive++;
        }

        if ($postReply->save()) {

            $user->votes_send++;

            if (!$user->save()) {
                foreach ($user->getMessages() as $message) {
                    $contentError = [
                        'status'  => 'error',
                        'message' => $message->getMessage()
                    ];
                    return $response->setJsonContent($contentError);
                }
            }
        }

        if ($user->id != $postReply->users_id) {
            $activity                       = new ActivityNotifications();
            $activity->users_id             = $postReply->users_id;
            $activity->posts_id             = $post->id;
            $activity->posts_replies_id     = $postReply->id;
            $activity->users_origin_id      = $user->id;
            $activity->type                 = ActivityNotifications::VOTE_UP_REPLY;
            $activity->save();
        }

        return $response->setJsonContent(['status' => 'OK']);
    }
}
