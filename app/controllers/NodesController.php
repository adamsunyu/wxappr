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
use Phosphorum\Models\Posts;
use Phosphorum\Models\Nodes;
use Phosphorum\Models\Users;
use Phosphorum\Models\UsersNodes;
use Phosphorum\Models\PostsReplies;
use Phalcon\Http\ResponseInterface;
use Phosphorum\Models\TopicTracking;
use Phosphorum\Models\PostsPollVotes;
use Phosphorum\Models\PostsPollOptions;
use Phosphorum\Utils\TokenTrait;
use Phosphorum\Models\UsersSocial;
use Phosphorum\Utils\WzImageHelper;
use Phosphorum\Forms\NodeCreateForm;
use Phosphorum\Forms\NodeEditForm;

/**
 * Class NodesController
 *
 * @package Phosphorum\Controllers
 */
class NodesController extends ControllerBase
{
    use TokenTrait;

    /**
     * Shows node list
     *
     * @param string $slug
     * @param int  $offset
     */
    public function indexAction($slug = 'platform', $offset = 0)
    {
        $itemBuilder = $this
            ->modelsManager
            ->createBuilder()
            ->from(['n' => 'Phosphorum\Models\Nodes']);

        $totalBuilder = $this
            ->modelsManager
            ->createBuilder()
            ->from(['n' => 'Phosphorum\Models\Nodes']);

        $itemBuilder
            ->columns(['n.*'])
            ->limit(self::POSTS_IN_PAGE)
            ->orderBy('n.number_followers DESC');

        $totalBuilder
            ->columns('COUNT(*) AS count');

        if ($slug != 'all') {
            $node = Nodes::findFirst(["slug = ?0", 'bind' => [$slug]]);

            if (!$node) {
                $this->flashSession->error('No nodes exist!');
                $this->response->redirect();
                return;
            }

            $itemBuilder->where('n.parent_id = ' . $node->id);
            $totalBuilder->where('n.parent_id = ' . $node->id);

            $this->tag->setTitle($node->name.'节点');
        } else {
            $itemBuilder->where('n.id >= 1000 ');
            $totalBuilder->where('n.id >= 1000 ');

            $this->tag->setTitle('全部节点');
        }

        $itemBuilder->andWhere("n.public = 'Y'");

        if ($offset > 0) {
            $itemBuilder->offset((int)$offset);
        }

        $number = $totalBuilder->getQuery()->setUniqueRow(true)->execute();
        $nodeList  = $itemBuilder->getQuery()->execute();
        $totalNodes = $number->count;

        $myNodes = null;

        if ($usersId = $this->session->get('identity')) {
            $myNodes = UsersNodes::find(["users_id = ?1", 'bind' => [1 => $usersId]]);
        }

        $nodeListWithStatus = [];

        // Update follow info
        foreach ($nodeList as $key => $node) {
            $isFollow = UsersNodes::checkFollow($myNodes, $node->id);
            $node->followed = $isFollow;
            $nodeListWithStatus[] = $node;
        }

        $hotNodes = null;
        if (!$usersId) {
            $hotNodes = Nodes::find(["id > 1000", 'order' => 'number_followers DESC', 'limit' => '10']);
        }

        $nodeCategories = Nodes::find(["parent_id = ?0 and public = 'Y'", 'bind' => [1]]);

        $statInfo = [Users::count(), Nodes::count(), Posts::count()];

        $this->view->setVars([
            'nodes'        => $nodeListWithStatus,
            'hotNodes'     => $hotNodes,
            'totalNodes'   => $totalNodes,
            'myNodes'      => $myNodes,
            'nodeCategories' => $nodeCategories,
            'current'      => $slug,
            'offset'       => $offset,
            'statistic'    => $statInfo,
            'paginatorUri' => "nodes/{$slug}"
        ]);
    }

    /**
     * Displays a node
     *
     * @param int $id node slug
     */
    public function viewAction($slug, $tab = 'post', $offset = 0)
    {
        if (!$node = Nodes::findFirstBySlug($slug)) {
            $this->flashSession->error('The node does not exist');
            $this->response->redirect();
            return;
        }

        if ($usersId = $this->session->get('identity')) {
            $user = Users::findFirstById($usersId);
        }

        $itemBuilder = $this
            ->modelsManager
            ->createBuilder();

        $totalBuilder = $this
            ->modelsManager
            ->createBuilder();

        $posts = null;
        $totalPosts = 0;

        $this->tag->setTitle($node->name . '话题');

        $itemBuilder
            ->from(['p' => 'Phosphorum\Models\Posts'])
            ->columns(['p.*'])
            ->where('p.nodes_id = '.$node->id)
            ->andWhere('p.deleted = 0')
            ->orderBy('p.modified_at DESC')
            ->limit(self::POSTS_IN_PAGE);

        $totalBuilder
            ->from(['p' => 'Phosphorum\Models\Posts'])
            ->where('p.nodes_id = '.$node->id)
            ->andWhere('p.deleted = 0')
            ->columns('COUNT(*) AS count');

        $number = $totalBuilder->getQuery()->setUniqueRow(true)->execute();
        $posts  = $itemBuilder->getQuery()->execute();
        $totalPosts = $number->count;

        if ($offset > 0) {
            $itemBuilder->offset((int)$offset);
        }

        $whoFollowed = UsersNodes::find(["nodes_id = ?1", 'bind' => [1 => $node->id], "limit" => 10]);

        $isFollowedByMe = false;
        if ($usersId = $this->session->get('identity')) {
            $count = UsersNodes::count(["users_id = ?0 AND nodes_id = ?1", 'bind' => [$usersId, $node->id]]);
            $isFollowedByMe = $count > 0;
        }

        $this->view->setVars([
            'canonical'      => "node/{$node->slug}/{$tab}",
            'node'           => $node,
            'followedByMe'   => $isFollowedByMe,
            'posts'          => $posts,
            'totalPosts'     => $totalPosts,
            'currentTab'     => $tab,
            'offset'         => $offset,
            'paginatorUri'   => "node/{$slug}/{$tab}",
            'whoFollowed'    => $whoFollowed
        ]);
    }

    /**
     * Create a new node
     */
    public function createAction()
    {
        if (!$usersId = $this->session->get('identity')) {
            $this->flashSession->error('你尚未登录');
            $this->response->redirect();
            return;
        }

        $user = Users::findFirstById($usersId);

        if (!$user) {
            $this->flashSession->error('用户不存在');
            $this->response->redirect();
            return;
        }

        $title = '创建节点';

        $this->tag->setTitle($title);

        $form = new NodeCreateForm();

        if ($this->request->isPost()) {

            if (!$this->checkTokenPost('create-node')) {
                $this->response->redirect();
                return;
            }

            $passValidate = true;

            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
                $passValidate = false;
            }

            $slug = $this->request->getPost('slug', 'trim');

            // Can't contain non english characters
            if (preg_match("/[^a-zA-Z0-9-]/i", $slug)) {
                $this->flash->error('节点域名不合法，不能使用英文字母、数字或-之外的字符');
                $passValidate = false;
            }

            if ($passValidate) {

                $parent_id = $this->request->getPost('nodeSelector');
                $name = $this->request->getPost('name', 'trim');
                $about = $this->request->getPost('aboutArea');

                $node                = new Nodes();
                $node->parent_id     = $parent_id;
                $node->creator_id    = $usersId;
                $node->name          = $name;
                $node->slug          = $slug;
                $node->about         = $about;

                if ($node->save()) {
                    $this->flashSession->success('节点创建成功，再给节点上传个图标吧');
                    $this->response->redirect("node-icon/{$node->id}");
                    return;
                } else {
                    $this->flash->error(join('<br>', $node->getMessages()));
                }
            }
        }

        $parameters = ["conditions" => "parent_id = ?0", "bind" => [$typeId],
                       "columns" => "id, name", "order" => "name ASC"];

        $nodeList = Nodes::find($parameters);

        $this->view->setVar('nodeList', $nodeList);
        $this->view->setVar('title', $title);

        $this->view->form = $form;
    }

    public function editAction($id)
    {
        if (!$usersId = $this->session->get('identity')) {
            $this->flashSession->error('你尚未登录');
            $this->response->redirect();
            return;
        }

        if (!$node = Nodes::findFirstById($id)) {
            $this->flashSession->error('节点不存在');
            $this->response->redirect();
            return;
        }

        if ($usersId != $node->creator_id) {
            $this->flashSession->error('你没有权限编辑此节点');
            $this->response->redirect();
            return;
        }

        $this->tag->setTitle('编辑节点: ' . $this->escaper->escapeHtml($node->name));

        $form = new NodeEditForm();

        if ($this->request->isPost()) {

            if (!$this->checkTokenPost('edit-node-'.$id)) {
                $this->response->redirect();
                return;
            }

            $passValidate = true;

            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
                $passValidate = false;
            }

            // 保证名字唯一性
            $name = $this->request->getPost('name', 'trim');
            if ($name != $node->name) {
                if (Nodes::count(['name = ?0','bind' => [$name]]) > 0) {
                    $this->flash->error('此节点名称已经存在');
                    $passValidate = false;
                }
            }

            // 保证域名唯一性
            $slug = $this->request->getPost('slug', 'trim');
            if ($slug != $node->slug) {

                if (Nodes::count(['slug = ?0','bind' => [$slug]]) > 0) {
                    $this->flash->error('此节点域名已经存在');
                    $passValidate = false;
                }

                // Can't contain non english characters
                if (preg_match("/[^a-zA-Z0-9-]/i", $slug)) {
                    $this->flash->error('节点域名不合法，不能使用英文字母、数字或-之外的字符');
                    $passValidate = false;
                }
            }

            if ($passValidate) {

                $about = $this->request->getPost('aboutArea');

                $node->name      = $name;
                $node->slug      = $slug;
                $node->about     = $about;
                $node->modified_at = time();

                if ($node->save()) {
                    $this->flashSession->success('节点修改成功');
                    $this->response->redirect("node/{$node->slug}");
                    return;
                } else {
                    $this->flashSession->error(join('<br>', $post->getMessages()));
                }
            }
        } else {
            $this->tag->displayTo('nodeSelector', $node->parent_id);
            $this->tag->displayTo('id', $node->id);
            $this->tag->displayTo('name', $node->name);
            $this->tag->displayTo('slug', $node->slug);
            $this->tag->displayTo('aboutArea', $node->about);
        }

        $nodeList = Nodes::find(["columns" => "id, name", "order" => "name ASC"]);

        $this->view->setVars([
            'node'         => $node,
            'nodeList'     => $nodeList
        ]);
        $this->view->form = $form;
    }

    public function iconAction($id)
    {
        if (!$usersId = $this->session->get('identity')) {
            $this->flashSession->error('你尚未登录');
            $this->response->redirect();
            return;
        }

        if (!$node = Nodes::findFirstById($id)) {
            $this->flashSession->error('The node does not exist');
            $this->response->redirect();
            return;
        }

        $this->tag->setTitle('节点图标');

        $this->view->setVar('node', $node);
    }

    public function uploadIconAction($id)
    {
        $response = new Response();

        $user = Users::findFirstById($this->session->get('identity'));

        if (!$user) {
            $contentlogIn = [
                'status'  => 'error',
                'message' => 'You must log in first'
            ];
            return $response->setJsonContent($contentlogIn);
        }

        $id = $this->request->getPost('nodeid');

        if (!$node = Nodes::findFirstById($id)) {
            $nodeError = [
                'status'  => 'error',
                'message' => 'The node does not exist'
            ];
            return $response->setJsonContent($nodeError);
        }

       if ($this->request->hasFiles() == true) {

           $baseLocation = 'icons/';
           $rawId = $node->iconRawId();

           foreach ($this->request->getUploadedFiles() as $file) {

               WzImageHelper::handleAvatar($baseLocation, $file, $rawId);

               // Update the avatar version
               if (!$node->icon_version) {
                   $node->icon_version = 1;
               } else {
                   $node->icon_version += 1;
               }

               $node->save();
           }

           $contentOk = [
               'status' => 'OK'
           ];

           return $response->setJsonContent($contentOk);
       }
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
            $this->flashSession->error('You must be logged first');
            return $this->response->redirect();
        }

        $is_moderator = $this->session->get('identity-moderator');

        $parameters = [
            "id = ?0 AND (users_id = ?1 OR 'Y' = ?2)",
            'bind' => [$id, $usersId, $is_moderator]
        ];

        if (!$post = Posts::findFirst($parameters)) {
            $this->flashSession->error('The discussion does not exist');
            return $this->response->redirect();
        }

        if (Posts::IS_DELETED == $post->deleted) {
            $this->flashSession->error("The post is already deleted");
            return $this->response->redirect();
        }

        if ($post->sticked == 'Y') {
            $this->flashSession->error("The discussion cannot be deleted because it's sticked");
            return $this->response->redirect();
        }

        $post->deleted = Posts::IS_DELETED;

        if ($post->save()) {
            $this->flashSession->success('删除成功');
            return $this->response->redirect();
        }

        $this->flashSession->error(join('<br>', $post->getMessages()));
        return $this->response->redirect();
    }

    /**
     * Follow the node
     *
     * @param string $id
     * @return ResponseInterface
     */
    public function followAction($id)
    {
        $response = new Response();

        $node = Nodes::findFirstById($id);
        if (!$node) {
            $contentNotExist = [
                'status'  => 'error',
                'message' => 'Node does not exist'
            ];
            return $response->setJsonContent($contentNotExist);
        }

        $user = Users::findFirstById($this->session->get('identity'));

        if (!$user) {
            $contentlogIn = [
                'status'  => 'error',
                'message' => 'You must log in first'
            ];
            return $response->setJsonContent($contentlogIn);
        }

        $userNode = UsersNodes::findFirst(
            ["conditions" => "users_id = ?1 AND nodes_id = ?2",
              "bind" => ['1' => $user->id, '2' => $node->id]]);

        if ($userNode) {
            $alreadyFollow = [
                'status'  => 'error',
                'message' => '你已经关注此节点'
            ];
            return $response->setJsonContent($alreadyFollow);
        }

        $userNode          = new UsersNodes();
        $userNode->nodes_id = $node->id;
        $userNode->users_id = $user->id;

        if (!$userNode->save()) {
            foreach ($userNode->getMessages() as $message) {
                $contentError = [
                    'status'  => 'error',
                    'message' => (string) $message->getMessage()
                ];
                return $response->setJsonContent($contentError);
            }
        } else {
            $node->number_followers += 1;
            $node->save();
        }

        $contentOk = [
            'status' => 'OK'
        ];

        return $response->setJsonContent($contentOk);
    }

    /**
     * Unfollow a node
     *
     * @param string $id
     * @return ResponseInterface
     */
    public function unfollowAction($id)
    {
        $response = new Response();

        $node = Nodes::findFirstById($id);
        if (!$node) {
            $contentNotExist = [
                'status'  => 'error',
                'message' => 'Node does not exist'
            ];
            return $response->setJsonContent($contentNotExist);
        }

        $user = Users::findFirstById($this->session->get('identity'));

        if (!$user) {
            $contentlogIn = [
                'status'  => 'error',
                'message' => 'You must log in first'
            ];
            return $response->setJsonContent($contentlogIn);
        }

        $userNode = UsersNodes::find(['nodes_id = ?0 AND users_id = ?1', 'bind' => [$node->id, $user->id]]);

        if ($userNode == null) {
            $contentAlreadyVote = [
                'status'  => 'error',
                'message' => '你已经取消成功'
            ];
            return $response->setJsonContent($contentAlreadyVote);
        }

        if ($userNode && !$userNode->delete()) {
            foreach ($userNode->getMessages() as $message) {
                $contentError = [
                    'status'  => 'error',
                    'message' => (string) $message->getMessage()
                ];
                return $response->setJsonContent($contentError);
            }
        } else {
            $node->number_followers -= 1;
            if ($node->number_followers < 0) {
                $node->number_followers = 0;
            }
            $node->save();
        }

        $contentOk = [
            'status' => 'OK'
        ];

        return $response->setJsonContent($contentOk);
    }

    /**
     * Displays a node homepage
     *
     * @param int $id node slug
     */
    public function homeAction($slug)
    {
        if (!$node = Nodes::findFirstBySlug($slug)) {
            $this->flashSession->error('The node does not exist');
            $this->response->redirect();
            return;
        }

        if ($usersId = $this->session->get('identity')) {
            $user = Users::findFirstById($usersId);
        }

        $this->tag->setTitle('节点'.$node->name.'主页');

        $whoFollowed = null;

        if ($usersId = $this->session->get('identity')) {
            $whoFollowed = UsersNodes::find(["nodes_id = ?1", 'bind' => [1 => $node->id], "limit" => 10]);
        }

        $this->view->setVars([
            'canonical'    => "node/{$node->slug}",
            'node'         => $node,
            'whoFollowed'  => $whoFollowed
        ]);
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
}
