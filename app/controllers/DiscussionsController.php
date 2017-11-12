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
use Phosphorum\Search\Indexer;
use Phosphorum\Models\Posts;
use Phosphorum\Models\PostsViews;
use Phosphorum\Models\PostsActivities;
use Phosphorum\Models\PostsVotes;
use Phosphorum\Models\PostsReplies;
use Phosphorum\Models\PostsSubscribers;
use Phosphorum\Models\Activities;
use Phalcon\Http\ResponseInterface;
use Phosphorum\Models\TopicTracking;
use Phosphorum\Models\PostsPollVotes;
use Phosphorum\Models\PostsPollOptions;
use Phosphorum\Models\UsersNodes;
use Phosphorum\Utils\TokenTrait;
use Phosphorum\Models\ActivityNotifications;
use Phosphorum\Models\UsersActivities;
use Phosphorum\Models\UsersSocial;
use Phosphorum\Models\Nodes;
use Phosphorum\Models\Tags;
use Phosphorum\Models\Bank;
use Phosphorum\Utils\HumanTime;
/**
 * Class DiscussionsController
 *
 * @package Phosphorum\Controllers
 */
class DiscussionsController extends ControllerBase
{
    use TokenTrait;

    /**
     * Shows latest posts using an order clause
     *
     * @param string $slug
     * @param int  $offset
     */
    public function indexAction($tab = 'new', $offset = 0)
    {
        $itemBuilder = $this
            ->modelsManager
            ->createBuilder()
            ->from(['p' => 'Phosphorum\Models\Posts']);

        $totalBuilder = $this
            ->modelsManager
            ->createBuilder()
            ->from(['p' => 'Phosphorum\Models\Posts']);;

        $itemBuilder
            ->columns(['p.*'])
            ->limit(self::POSTS_IN_PAGE);

        $totalBuilder
            ->columns('COUNT(*) AS count');

        switch ($tab) {
            case 'new':
                $this->tag->setTitle('最新话题');
                $itemBuilder->orderBy('p.sticked DESC, p.modified_at DESC');
                break;
            case 'hot':
                $this->tag->setTitle('热门话题');
                $itemBuilder->orderBy('p.sticked DESC, p.number_views DESC');
                break;
            case 'ideas':
                $this->tag->setTitle('分享');
                $itemBuilder->where('p.nodes_id=1');
                $totalBuilder->where('p.nodes_id=1');
                $itemBuilder->orderBy('p.sticked DESC, p.modified_at DESC');
                break;
            case 'questions':
                $this->tag->setTitle('问答');
                $itemBuilder->where('p.nodes_id=2');
                $totalBuilder->where('p.nodes_id=2');
                $itemBuilder->orderBy('p.sticked DESC, p.modified_at DESC');
                break;
            default:
                $this->tag->setTitle('最新话题');
                $itemBuilder->orderBy('p.sticked DESC, p.modified_at DESC');
        }

        $notDeleteConditions = 'p.deleted = 0';
        $itemBuilder->andWhere($notDeleteConditions);
        $totalBuilder->andWhere($notDeleteConditions);

        if ($offset > 0) {
            $itemBuilder->offset((int)$offset);
        }

        $posts = $itemBuilder->getQuery()->execute();
        $number = $totalBuilder->getQuery()->setUniqueRow(true)->execute();
        $totalPosts = $number->count;

        parent::initPublicSidebar();

        $paginator = "bbs/".$tab;

        $this->view->setVars([
            'posts'        => $posts,
            'totalPosts'   => $totalPosts,
            'currentTab'   => $tab,
            'offset'       => $offset,
            'paginatorUri' => $paginator
        ]);
    }

    /**
     * Shows latest news using an order clause
     *
     * @param string $slug
     * @param int  $offset
     */
    public function newsAction($tab = 'new', $offset = 0)
    {
        if ($usersId = $this->session->get('identity')) {
            $user = Users::findFirstById($usersId);
        }

        $itemBuilder = $this
            ->modelsManager
            ->createBuilder()
            ->from(['p' => 'Phosphorum\Models\Posts']);

        $totalBuilder = $this
            ->modelsManager
            ->createBuilder()
            ->from(['p' => 'Phosphorum\Models\Posts']);;

        $itemBuilder
            ->columns(['p.*'])
            ->limit(self::POSTS_IN_PAGE);

        $totalBuilder
            ->columns('COUNT(*) AS count');

        switch ($tab) {
            case 'new':
                $this->tag->setTitle('最新话题');
                $itemBuilder->orderBy('p.sticked DESC, p.modified_at DESC');
                break;
            case 'hot':
                $this->tag->setTitle('热门话题');
                $itemBuilder->orderBy('p.sticked DESC, p.number_views DESC, p.number_replies');
                break;
            default:
                $this->tag->setTitle('最新话题');
                $itemBuilder->orderBy('p.sticked DESC, p.modified_at DESC');
        }

        $notDeleteConditions = 'p.deleted = 0';
        $itemBuilder->andWhere($notDeleteConditions);
        $totalBuilder->andWhere($notDeleteConditions);

        if ($offset > 0) {
            $itemBuilder->offset((int)$offset);
        }

        $statInfo = [Users::count(), Nodes::count(), Posts::count()];

        $posts = $itemBuilder->getQuery()->execute();
        $number = $totalBuilder->getQuery()->setUniqueRow(true)->execute();
        $totalPosts = $number->count;

        $myNodes = null;

        if ($usersId = $this->session->get('identity')) {
            $myNodes = UsersNodes::find(["users_id = ?1", 'bind' => [1 => $usersId]]);
        }

        $activities = UsersActivities::find(["order" => "page_views DESC", "limit" => 10]);

        $paginator = "topics/".$tab;

        $this->view->setVars([
            'posts'        => $posts,
            'totalPosts'   => $totalPosts,
            'currentTab'   => $tab,
            'offset'       => $offset,
            'paginatorUri' => $paginator,
            'myNodes'      => $myNodes,
            'statistic'    => $statInfo,
            'active_users' => $activities
        ]);
    }


    /**
     * Post information
     */
    public function postAction($category)
    {
        if (!$usersId = $this->session->get('identity')) {
            $this->flashSession->error('你尚未登录');
            $this->response->redirect();
            return;
        }

        if (!$user = Users::findFirstById($usersId)) {
            $this->flashSession->error('你尚未登录');
            $this->response->redirect();
            return;
        }

        $actionName = '';

        if ($category == 'idea') {
            $actionName = '发布话题';
            $nodeId = 1;
        } elseif ($category == 'question') {
            $actionName = '提问题';
            $nodeId = 2;
        }

        if ($this->request->isPost()) {

            if (!$this->checkTokenPost('create-note')) {
                $this->response->redirect();
                return;
            }

            $title = $this->request->getPost('title', 'trim');
            $nodeId = $this->request->getPost('nodeId');

            $post                = new Posts();
            $post->users_id      = $usersId;
            $post->nodes_id      = $nodeId;
            $post->title         = $title;
            $post->slug          = $this->slug->uniqueString();
            $post->content       = $this->request->getPost('contentArea');

            if ($post->save()) {

                Bank::handlePostNew($user);

                $this->response->redirect("topic/{$post->id}");
                return;
            } else {
                $this->flash->error(join('<br>', $post->getMessages()));
            }
        }

        // $node = null;
        // if ($nodeId > 0) {
        //     $parameters = ["conditions" => "id = ?0", "bind" => [$nodeId]];
        //     $node = Nodes::findFirst($parameters);
        // }
        //
        // $myNodes = UsersNodes::find(["users_id = ?1", 'bind' => [1 => $usersId]]);
        //
        // $nodeList = Nodes::find([
        //     'id >= 0',
        //     "columns" => "id, name",
        //     "orderBy" => "id ASC"
        // ]);
        //
        // $newNodeList = Nodes::reorderNodes($myNodes, $nodeList);

        $parameters = [
            "users_id = ?0",
            'bind'    => [$usersId],
            "columns" => "id, title",
            "limit"   => 10,
            'nodes_id'  => $nodeId,
            "order" => "created_at DESC"
        ];
        $myPosts = Posts::find($parameters);

        $this->tag->setTitle($actionName);

        $this->view->setVar('nodeId', $nodeId);
        $this->view->setVar('actionName', $actionName);
    }

    /**
     * Post link information
     */
    public function postLinkAction()
    {
        if (!$usersId = $this->session->get('identity')) {
            $this->flashSession->error('你尚未登录');
            $this->response->redirect();
            return;
        }

        if (!$user = Users::findFirstById($usersId)) {
            $this->flashSession->error('你尚未登录');
            $this->response->redirect();
            return;
        }

        $actionName = '分享链接';
        $nodeId = 3;

        if ($this->request->isPost()) {

            if (!$this->checkTokenPost('create-note')) {
                $this->response->redirect();
                return;
            }

            $link = $this->request->getPost('link', 'trim');
            $title = $this->request->getPost('title', 'trim');
            $content = $this->request->getPost('suggestArea');

            $post                = new Posts();
            $post->users_id      = $usersId;
            $post->nodes_id      = $nodeId;
            $post->link          = $link;
            $post->title         = $title;
            $post->slug          = $this->slug->uniqueString();
            $post->content       = $content;

            if ($post->save()) {

                Bank::handlePostNew($user);

                $this->response->redirect("topic/{$post->id}");
                return;
            } else {
                $this->flash->error(join('<br>', $post->getMessages()));
            }
        }

        $parameters = [
            "users_id = ?0",
            'bind'    => [$usersId],
            "columns" => "id, title",
            "limit"   => 10,
            'nodes_id'  => $nodeId,
            "order" => "created_at DESC"
        ];

        $this->tag->setTitle($actionName);

        $this->view->setVar('nodeId', $nodeId);
        $this->view->setVar('actionName', $actionName);
    }

    /**
     * This shows the create post form and also store the related post
     *
     * @param int $id Post ID
     */
    public function editLinkAction($id)
    {
        if (!$usersId = $this->session->get('identity')) {
            $this->flashSession->error('你尚未登录');
            $this->response->redirect();
            return;
        }

        if (!$user = Users::findFirstById($usersId)) {
            $this->flashSession->error('你尚未登录');
            $this->response->redirect();
            return;
        }

        $parameters = [
            "id = ?0 AND (users_id = ?1 OR 'Y' = ?2)",
            'bind' => [$id, $usersId, $this->session->get('identity-moderator')]
        ];

        if (!$post = Posts::findFirst($parameters)) {
            $this->flashSession->error('The discussion does not exist');
            $this->response->redirect();
            return;
        }

        if ($this->request->isPost()) {

            $link = $this->request->getPost('link', 'trim');
            $title = $this->request->getPost('title', 'trim');
            $content = $this->request->getPost('suggestArea');

            $connection = $this->getDI()->getShared('db');
            $connection->begin();

            $post->link          = $link;
            $post->title         = $title;
            $post->content       = $content;
            $post->edited_at     = time();

            if ($post->save()) {
                $connection->commit();
                $this->response->redirect("topic/{$post->id}");
                return;
            } else {
                $connection->rollback();
                $this->flashSession->error(join('<br>', $post->getMessages()));
            }
        } else {

            $content = htmlspecialchars($post->content, ENT_QUOTES);

            $this->tag->displayTo('id', $post->id);
            $this->tag->displayTo('link', $post->link);
            $this->tag->displayTo('title', $post->title);
            $this->tag->displayTo('suggestArea', $content);
        }

        $title = '编辑链接';

        $this->tag->setTitle($title);

        $this->view->setVars([
            'post'      => $post,
            'title'     => $title
        ]);
    }

    /**
     * Stick post.
     *
     * @param int $id Post ID
     * @return ResponseInterface
     */
    public function stickAction($postId)
    {
        if (!$usersId = $this->session->get('identity')) {
            $error = [
                'status'  => 'error',
                'message' => '你尚未登录'
            ];
            return $this->response->setJsonContent($error);
        }

        if (!$user = Users::findFirstById($usersId)) {
            $error = [
                'status'  => 'error',
                'message' => '你尚未登录'
            ];
            return $this->response->setJsonContent($error);
        }

        if (!$post = Posts::findFirstById($postId)) {
            $error = [
                'status'  => 'error',
                'message' => '主题不存在'
            ];
            return $this->response->setJsonContent($error);
        }

        if($post->sticked == 'Y') {
            $error = [
                'status'  => 'error',
                'message' => '已置顶成功'
            ];
            return $this->response->setJsonContent($error);
        }

        if (Posts::IS_DELETED == $post->deleted) {
            $error = [
                'status'  => 'error',
                'message' => '主题已删除'
            ];
            return $this->response->setJsonContent($error);
        }

        $stickAmount = (int)$this->request->getPost('stickAmount');

        if ($stickAmount > $user->money) {
            $error = [
                'status'  => 'error',
                'message' => '你的资产不足'
            ];
            return $this->response->setJsonContent($error);
        }

        $stick_endtime = 0;

        if ($stickAmount == 24) {
            $stick_endtime = time() + 12 * 60 * 60;
        } else if($stickAmount == 48) {
            $stick_endtime = time() + 24 * 60 * 60;
        } else if($stickAmount == 72) {
            $stick_endtime = time() + 36 * 60 * 60;
        }

        $post->sticked = Posts::IS_STICKED;
        $post->sticked_owner = $usersId;
        $post->sticked_endtime = $stick_endtime;

        if ($post->save()) {
            $this->flashSession->success('置顶成功');

            Bank::handleSelfSticky($user, $stickAmount);
        }

        return $this->response->setJsonContent(['status' => 'OK']);
    }

    /**
     * Unstick post.
     *
     * @param int $id Post ID
     * @return ResponseInterface
     */
    public function unstickAction($id)
    {
        if (!$this->checkTokenGet('post-' . $id)) {
            return $this->response->redirect();
        }

        if (!$usersId = $this->session->get('identity')) {
            $this->flashSession->error('You must be logged first');
            $this->response->redirect();
            return $this->response->redirect();
        }

        $parameters = [
            "id = ?0 AND sticked = ?1 AND 'Y' = ?2",
            'bind' => [$id, Posts::IS_STICKED, $this->session->get('identity-moderator')]
        ];

        if (!$post = Posts::findFirst($parameters)) {
            $this->flashSession->error('The discussion does not exist');
            $this->response->redirect();
            return $this->response->redirect();
        }

        if (Posts::IS_DELETED == $post->deleted) {
            $this->flashSession->error("The post is deleted");
            return $this->response->redirect();
        }

        $post->sticked = Posts::IS_UNSTICKED;
        if ($post->save()) {
            $this->flashSession->success('取消置顶成功');
            return $this->response->redirect();
        }

        $this->flashSession->error(join('<br>', $post->getMessages()));
        return $this->response->redirect();
    }

    /**
     * This shows the create post form and also store the related post
     *
     * @param int $id Post ID
     */
    public function editAction($id)
    {
        if (!$usersId = $this->session->get('identity')) {
            $this->flashSession->error('You must be logged first');
            $this->response->redirect();
            return;
        }

        $parameters = [
            "id = ?0 AND (users_id = ?1 OR 'Y' = ?2)",
            'bind' => [$id, $usersId, $this->session->get('identity-moderator')]
        ];

        if (!$post = Posts::findFirst($parameters)) {
            $this->flashSession->error('The discussion does not exist');
            $this->response->redirect();
            return;
        }

        if ($this->request->isPost()) {
            if (!$this->checkTokenPost('edit-post-'.$id)) {
                $this->response->redirect();
                return;
            }

            $nodeId = $this->request->getPost('nodeSelector');
            $title   = $this->request->getPost('title', 'trim');
            $content = $this->request->getPost('contentArea');

            $connection = $this->getDI()->getShared('db');
            $connection->begin();

            $post->nodes_id      = $nodeId;
            $post->title         = $title;
            $post->content       = $content;
            $post->edited_at     = time();

            if ($post->save()) {
                $connection->commit();
                $this->response->redirect("topic/{$post->id}");
                return;
            } else {
                $connection->rollback();
                $this->flashSession->error(join('<br>', $post->getMessages()));
            }
        } else {

            $content = htmlspecialchars($post->content, ENT_QUOTES);

            $this->tag->displayTo('id', $post->id);
            $this->tag->displayTo('title', $post->title);
            $this->tag->displayTo('contentArea', $content);
            $this->tag->displayTo('nodeSelector', $post->nodes_id);
        }

        $myNodes = UsersNodes::find(["users_id = ?1", 'bind' => [1 => $usersId]]);

        $nodeList = Nodes::find([
            'id >= 0',
            "columns" => "id, name",
            "orderBy" => "id ASC"
        ]);

        $newNodeList = Nodes::reorderNodes($myNodes, $nodeList);

        $this->tag->setTitle('编辑: ' . $this->escaper->escapeHtml($post->title));

        $this->view->setVars([
            'post'         => $post,
            'nodeList'     => $newNodeList
        ]);
    }

    /**
     * Deletes the Post
     *
     * @param int $id
     * @return ResponseInterface
     */
    public function deleteAction($id)
    {
        if (!$this->checkTokenGet('post-' . $id)) {
            return $this->response->redirect();
        }

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

        $parameters = [
            "id = ?0 AND (users_id = ?1 OR 'Y' = ?2)",
            'bind' => [$id, $usersId, $is_moderator]
        ];

        if (!$post = Posts::findFirst($parameters)) {
            $this->flashSession->error('主题不存在');
            return $this->response->redirect();
        }

        if (Posts::IS_DELETED == $post->deleted) {
            $this->flashSession->error("主题已删除");
            return $this->response->redirect();
        }

        if ($post->sticked == 'Y') {
            $this->flashSession->error("置顶主题不能删除");
            return $this->response->redirect();
        }

        $post->deleted = Posts::IS_DELETED;

        if ($post->save()) {

            if ($is_moderator) {
                Bank::handleModeratePost($post->user, $post);
            } else {
                Bank::handleDeletePost($post->user);
            }

            $usersId = $this->session->get('identity');

            $this->flashSession->success('删除成功');
            return $this->response->redirect();
        }

        $this->flashSession->error(join('<br>', $post->getMessages()));
        return $this->response->redirect();
    }

    /**
     * Subscribe to a post to receive e-mail notifications
     *
     * @param string $id
     * @return ResponseInterface
     */
    public function subscribeAction($id)
    {
        if (!$this->checkTokenGet('post-' . $id)) {
            return $this->response->redirect();
        }

        $usersId = $this->session->get('identity');
        if (!$usersId) {
            $this->flashSession->error('You must be logged first');
            return $this->response->redirect();
        }

        $post = Posts::findFirstById($id);
        if (!$post) {
            $this->flashSession->error('The discussion does not exist');
            return $this->response->redirect();
        }

        $subscription = PostsSubscribers::findFirst([
            'posts_id = ?0 AND users_id = ?1',
            'bind' => [$post->id, $usersId]
        ]);
        if (!$subscription) {
            $subscription             = new PostsSubscribers();
            $subscription->posts_id   = $post->id;
            $subscription->users_id   = $usersId;
            $subscription->created_at = time();
            if ($subscription->save()) {
                $this->flashSession->notice('You are now subscribed to this post');
            }
        }

        return $this->response->redirect('topic/' . $post->id);
    }

    /**
     * Unsubscribe from a post of receiving e-mail notifications
     *
     * @param string $id
     * @return ResponseInterface
     */
    public function unsubscribeAction($id)
    {
        if (!$this->checkTokenGet('post-' . $id)) {
            return $this->response->redirect();
        }

        $usersId = $this->session->get('identity');
        if (!$usersId) {
            $this->flashSession->error('You must be logged first');
            return $this->response->redirect();
        }

        $post = Posts::findFirstById($id);
        if (!$post) {
            $this->flashSession->error('The discussion does not exist');
            return $this->response->redirect();
        }

        $subscription = PostsSubscribers::findFirst([
            'posts_id = ?0 AND users_id = ?1',
            'bind' => [$post->id, $usersId]
        ]);
        if ($subscription) {
            $this->flashSession->notice('You were successfully unsubscribed from this post');
            $subscription->delete();
        }

        return $this->response->redirect('topic/' . $post->id);
    }

    /**
     * Displays a post and its comments
     *
     * @param int $id Post ID
     * @param string $slug Post slug [Optional]
     */
    public function viewAction($id, $slug = '')
    {
        $id = (int)$id;

        $user = null;

        // Check read / unread topic
        if ($usersId = $this->session->get('identity')) {
            $user = Users::findFirstById($usersId);
        }

        if (!$this->request->isPost()) {
            // Find the post using get
            if (!$post = Posts::findFirstById($id)) {
                $this->flashSession->error('主题不存在');
                $this->response->redirect();
                return;
            }

            if ($post->deleted) {
                $this->flashSession->error('主题已被删除');
                $this->response->redirect();
                return;
            }

            $difference = $post->getDifference();
            $this->view->setVar('is_edited', !empty(trim($difference)));

            $ipAddress = $this->request->getClientAddress();

            $parameters = [
                'posts_id = ?0 AND ipaddress = ?1',
                'bind' => [$id, $ipAddress]
            ];

            // A view is stored by ip address
            if (!$viewed = PostsViews::count($parameters)) {

                // Increase the number of views in the post
                $post->number_views++;

                $postView            = new PostsViews();
                $postView->post      = $post;
                $postView->ipaddress = $ipAddress;

                // No need care about the post view error
                $postView->save();

                // Calculate the poplular posts
                $postActivity = PostsActivities::findFirstByPostsId($post->id);

                if (!$postActivity) {
                    $postActivity             = new PostsActivities();
                    $postActivity->post       = $post;
                    $postActivity->page_views = 1;
                    $postActivity->created_at = time();
                } else {
                    $postActivity->page_views += 1;
                }

                // No need care about the post activity error
                $postActivity->save();
            }

            if (!$usersId) {
                // Enable cache
                $this->view->cache(['key' => 'post-' . $id]);

                // Check for a cache
                if ($this->viewCache->exists('post-' . $id)) {
                    return;
                }
            }

            // Generate canonical meta
            $this->view->setVars([
                'canonical' => "topic/{$post->id}"
            ]);
        } else {

            if (!$this->checkTokenPost('post-' . $id)) {
                $this->response->redirect();
                return;
            }

            // Find the post using POST
            if (!$post = Posts::findFirstById($this->request->getPost('id'))) {
                $this->flashSession->error('主题不存在');
                $this->response->redirect();
                return;
            }

            if ($post->deleted) {
                $this->flashSession->error('主题已被删除');
                $this->response->redirect();
                return;
            }

            if ($content = $this->request->getPost('commentArea', 'trim')) {

                if (!$user = Users::findFirstById($usersId)) {
                    $this->flashSession->error('你尚未登录');
                    $this->response->redirect();
                    return;
                }

                $post->number_replies++;
                $post->modified_at = time();
                $post->save();

                $postReply                 = new PostsReplies();
                $postReply->post           = $post;
                $postReply->users_id       = $usersId;
                $postReply->content        = $content;

                $replyId = $this->request->getPost('reply-id', 'int');

                if ($replyId) {
                    $reply = PostsReplies::findFirstById($replyId);
                    $postReply->in_reply_to_id = $replyId;
                    $postReply->in_reply_to_user = $reply->users_id;
                }

                if ($postReply->save()) {

                    if ($post->user->id != $user->id) {

                        $isFirstReply = false;
                        if ($post->number_replies == 1) {
                            $isFirstReply = true;
                        }
                        Bank::handlePostReply($user, $isFirstReply);
                    }

                    return $this->response->redirect("topic/{$post->id}#C{$postReply->id}");
                } else {
                    $this->flash->error(join('<br>', $postReply->getMessages()));
                }
            }
        }

        $voting = [];

        parent::initPublicSidebar();

        // Set the post name as title - escaping it first
        $this->tag->setTitle($this->escaper->escapeHtml($post->title));

        $parameters = [
            "posts_id = ?0",
            'bind' => [$id],
            "limit" => 30,
            "order" => "votes_up DESC"
        ];

        $replies = PostsReplies::find($parameters);

        $parameters = [
            "limit" => 10,
            "order" => "page_views DESC"
        ];
        $hotPosts = PostsActivities::find($parameters);

        // Leave the single quote not encoding
        $content = htmlspecialchars($post->content, ENT_COMPAT);

        $parameters = ["conditions" => "posts_id = ?0 AND users_id = ?1",
                       "bind" => [$post->id, $user->id]];

        $postVote = PostsVotes::findFirst($parameters);

        if ($postVote) {
            $this->view->setVar('votedType', $postVote->vote_type);
        } else {
            $this->view->setVar('votedType', 0);
        }

        $this->view->setVars([
            'post'       => $post,
            'content'    => $content,
            'replies'    => $replies,
            'otherUser'  => $post->user,
            'user'       => $user,
            'hotPosts'   => $hotPosts
        ]);
    }

    /**
     * Shows the latest modification made to a post
     *
     * @param int $id The Post id.
     */
    public function historyAction($id = 0)
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        /**
         * Find the post using get
         */
        $post = Posts::findFirstById($id);
        if (!$post) {
            $this->view->setVar('difference', 'The discussion does not exist or it has been deleted.');
            return;
        }

        $this->view->setVar('difference', $post->getDifference() ?: 'No history available to show');
    }

    /**
     * Votes a post
     *
     * @param int $id The post ID.
     * @return ResponseInterface
     */
    public function voteAction($id = 0, $voteType = 1)
    {
        $post = Posts::findFirstById($id);
        if (!$post) {
            $contentNotExist = [
                'status'  => 'error',
                'message' => '主题不存在'
            ];
            return $this->response->setJsonContent($contentNotExist);
        }

        $user = Users::findFirstById($this->session->get('identity'));
        if (!$user) {
            $contentlogIn = [
                'status'  => 'error',
                'message' => '你尚未登录'
            ];
            return $this->response->setJsonContent($contentlogIn);
        }

        if (!$this->checkTokenGetJson('post-' . $id)) {
            $csrfTokenError = [
                'status'  => 'error',
                'message' => '页面已失效，请刷新'
            ];
            return $this->response->setJsonContent($csrfTokenError);
        }

        $parameters = ["conditions" => "posts_id = ?0 AND users_id = ?1",
                       "bind" => [$id, $user->id]];

        $postVote = PostsVotes::findFirst($parameters);

        if ($postVote) {

            if($postVote->vote_type == $voteType) {
                $contentOk = [
                    'status' => 'OK'
                ];
                return $this->response->setJsonContent($contentOk);
            } else {

                $postVote->vote_type = $voteType;

                if ($voteType == PostsVotes::VOTE_DOWN) {

                    if ($post->votes_up >= 1) {
                        $post->votes_up--;
                    }

                    $post->votes_down++;

                    if ($post->user->votes_receive >= 1) {
                        $post->user->votes_receive--;
                    }
                    if ($user->votes_send >= 1) {
                        $user->votes_send--;
                        $user->save();
                    }

                    // Delete the notification
                    $parameters = ["conditions" => "users_id = ?0 AND posts_id = ?1 AND users_origin_id = ?2 AND type='VP'",
                                   "bind" => [$post->users_id, $post->id, $user->id]];
                    $notification = ActivityNotifications::findFirst($parameters);

                    if ($notification) {
                        $notification->delete();
                    }
                } else if($voteType == PostsVotes::VOTE_UP) {

                    if ($post->votes_down >= 1) {
                        $post->votes_down--;
                    }
                }
            }

        } else {
            $postVote            = new PostsVotes();
            $postVote->posts_id  = $post->id;
            $postVote->users_id  = $user->id;
            $postVote->vote_type = $voteType;
        }

        if ($postVote->save()) {

            if ($voteType == PostsVotes::VOTE_UP) {

                if ($post->users_id != $user->id) {

                    $post->votes_up++;

                    $post->user->votes_receive++;
                    $user->votes_send++;
                    $user->save();

                    $activity                       = new ActivityNotifications();
                    $activity->users_id             = $post->users_id;
                    $activity->posts_id             = $post->id;
                    $activity->users_origin_id      = $user->id;
                    $activity->type                 = ActivityNotifications::VOTE_UP_POST;
                    $activity->save();
                } else {
                    $post->votes_up++;
                }
            }
        } else {
            $contentError = [
                'status'  => 'error',
                'message' => (string) $postVote->getMessages()
            ];
            return $this->response->setJsonContent($contentError);
        }

        if (!$post->save()) {
            $error = [
                'status'  => 'error',
                'message' => (string) $post->getMessages()
            ];
            return $this->response->setJsonContent($error);
        }

        $contentOk = [
            'status' => 'OK'
        ];

        return $this->response->setJsonContent($contentOk);
    }

    /**
     * Perform the search of posts only searching in the title
     */
    public function searchAction()
    {

        $this->tag->setTitle('Search Results');

        $q = $this->request->getQuery('q');

        $indexer = new Indexer();

        $posts = $indexer->search(['title' => $q, 'content' => $q], 50, true);
        if (!count($posts)) {
            $posts = $indexer->search(['title' => $q], 50, true);
            if (!count($posts)) {
                $this->flashSession->notice('There are no search results');
                return $this->response->redirect();
            }
        }

        $paginator = new \stdClass;
        $paginator->count = 0;

        $this->view->setVars([
            'posts'        => $posts,
            'totalPosts'   => $paginator,
            'currentOrder' => null,
            'offset'       => 0,
            'paginatorUri' => 'search'
        ]);
    }

    /**
     * Finds related posts
     *
     * @return ResponseInterface
     */
    public function findRelatedAction()
    {
        $response = new Response();
        $indexer  = new Indexer();
        $results  = [];

        if ($this->request->has('title')) {
            if ($title = $this->request->getPost('title', 'trim')) {
                $results = $indexer->search(['title' => $title], 5);
            }
        }

        $contentOk = [
            'status'  => 'OK',
            'results' => $results
        ];

        return $response->setJsonContent($contentOk);
    }

    /**
     * Finds related posts
     */
    public function showRelatedAction()
    {
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

        $post = Posts::findFirstById($this->request->getPost('id'));
        if ($post) {
            $indexer = new Indexer();
            $posts = $indexer->search(
                [
                    'title'    => $post->title
                ],
                5,
                true
            );

            if (count($posts) == 0) {
                $posts = $indexer->search(['title' => $post->title], 5, true);
            }
            $this->view->setVar('posts', $posts);
        } else {
            $this->view->setVar('posts', []);
        }
    }
}
