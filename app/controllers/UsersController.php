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
use Phosphorum\Models\Users;
use Phosphorum\Models\Posts;
use Phosphorum\Models\UsersFollowers;
use Phosphorum\Models\PostsReplies;
use Phosphorum\Models\MatchesComments;
use Phosphorum\Models\Activities;
use Phosphorum\Utils\TokenTrait;
use Phosphorum\Forms\ChangePasswordForm;
use Phosphorum\Forms\ChangeNameForm;
use Phosphorum\Forms\ChangeDomainForm;
use Phosphorum\Forms\ChangeCityForm;
use Phosphorum\Forms\SocialForm;
use Phosphorum\Models\ChangesPassword;
use Phosphorum\Models\ChangesNickname;
use Phosphorum\Models\ChangesLogin;
use Phosphorum\Models\UsersSocial;
use Phosphorum\Models\UsersNodes;
use Phosphorum\Models\Nodes;
use Phosphorum\Models\Cities;
use Phosphorum\Models\ActivityNotifications;
use Phosphorum\Utils\WzImageHelper;
use Phosphorum\Models\UsersBankbook;
use Phosphorum\Models\UsersCodes;
use Phosphorum\Utils\HumanTime;
use Phosphorum\Models\MessagesInbox;
use Phosphorum\Models\MessagesOutbox;

use Phalcon\Tag;

use DateTime;
use DateInterval;

/**
 * Class UsersController
 *
 * @package Phosphorum\Controllers
 */
class UsersController extends ControllerBase
{
    use TokenTrait;

    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Shows users rank list
     *
     * @param string $slug
     * @param int  $offset
     */
    public function indexAction($tab = 'getvotes', $offset = 0)
    {
        $this->tag->setTitle('用户列表');

        $itemBuilder = $this
            ->modelsManager
            ->createBuilder()
            ->from(['n' => 'Phosphorum\Models\Users']);

        $totalBuilder = $this
            ->modelsManager
            ->createBuilder()
            ->from(['n' => 'Phosphorum\Models\Users']);

        $itemBuilder
            ->columns(['n.*'])
            ->limit(self::POSTS_IN_PAGE);

        $totalBuilder
            ->columns('COUNT(*) AS count');

        if ($tab == 'recieve') {
            $itemBuilder->orderBy('n.votes_receive DESC');
        } else if($tab == 'send') {
            $itemBuilder->orderBy('n.votes_send DESC');
        } else if($tab == 'money') {
            $itemBuilder->orderBy('n.money DESC');
        }

        if ($offset > 0) {
            $itemBuilder->offset((int)$offset);
        }

        $number = $totalBuilder->getQuery()->setUniqueRow(true)->execute();
        $userList  = $itemBuilder->getQuery()->execute();
        $totalUsers = $number->count;

        $statInfo = [Users::count(), Nodes::count(), Posts::count()];

        $this->view->setVars([
            'users'        => $userList,
            'totalUsers'   => $totalUsers,
            'currentTab'   => $tab,
            'offset'       => $offset,
            'statistic'    => $statInfo,
            'paginatorUri' => "users/{$slug}"
        ]);
    }

    /**
     * Shows the user profile
     *
     * @param int    $id       User id
     * @param string $username User name
     */
    public function viewAction($login, $tab = 'activity')
    {
        $user = Users::findFirstByLogin($login);

        if (!$user) {
            $this->flashSession->error('The user does not exist');
            $this->response->redirect();
            return;
        }

        if ($tab == 'index') {
            $followings = UsersFollowers::find(['followers_id = ?0', 'bind' => [$user->id]]);
            $this->view->setVar('myFollowings', $followings);
        } else if($tab == 'activity') {
            $parametersActivities = [
                'users_id = ?0',
                'bind'  => [$user->id],
                'order' => 'created_at DESC',
                'limit' => 15
            ];
            $this->view->setVar('activities', Activities::find($parametersActivities));
        } else if($tab == 'followers') {
            $followers = UsersFollowers::find(['users_id = ?0', 'bind' => [$user->id]]);
            $this->view->setVar('myFollowers', $followers);

        } else if($tab == 'node') {
            $this->view->setVar('myNodes', UsersNodes::find(['users_id = ?0', 'bind' => [$user->id]]));
        }

        $isFollowedByMe = false;

        if ($this->myself) {

            $userFollow = UsersFollowers::findFirst(
                ["conditions" => "users_id = ?1 AND followers_id = ?2",
                 "bind" => ['1' => $user->id, '2' => $this->myself->id]]
            );

            if ($userFollow) {
                $isFollowedByMe = true;
            }
        }

        $this->setUserStats($user);

        $this->view->setVars([
            'user'          => $user,
            'currentTab'    => $tab,
            'followedByMe'  => $isFollowedByMe
        ]);

        $this->tag->setTitle($this->escaper->escapeHtml($user->name));
    }

    public function mytopicAction($id)
    {
        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $this->response->redirect();
            return;
        }

        $user = Users::findFirstById($usersId);

        if (!$user) {
            $this->response->redirect();
            return;
        }

        $parameters = [
            'id = ?0 AND users_id = ?1',
            'bind' => [$id, $usersId]
        ];

        if (!$post = Posts::findFirst($parameters)) {
            $this->flashSession->error('主题不存在');
            $this->response->redirect();
            return;
        }

        $this->setUserStats($user);

        $this->view->setVars([
            'user'      => $user,
            'post'      => $post,
            'canonical' => "topic/{$post->id}"
        ]);
    }

    public function uploadAction()
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

       // Check if the user has uploaded files
       if ($this->request->hasFiles() == true) {

           $baseLocation = 'avatars/';
           $rawId = $user->avatarRawId();

           foreach ($this->request->getUploadedFiles() as $file) {

               WzImageHelper::handleAvatar($baseLocation, $file, $rawId);

               // Update the avatar version
               if (!$user->avatar_version) {
                   $user->avatar_version = 1;
               } else {
                   $user->avatar_version += 1;
               }

               $user->save();
           }

           $contentOk = [
               'status' => 'OK'
           ];

           return $response->setJsonContent($contentOk);
       }
    }

    /**
     * Allow to change your basic settings
     */
    public function settingsAction()
    {
        $this->tag->setTitle('我的设置');

        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $this->response->redirect();
            return;
        }

        $user = Users::findFirstById($usersId);

        if (!$user) {
            $this->response->redirect();
            return;
        }

        if ($this->request->isPost()) {

            if (!$this->checkTokenPost('settings')) {
                $this->response->redirect();
                return;
            }

            $user->timezone      = $this->request->getPost('timezone');
            $user->notifications = $this->request->getPost('notifications');
            $user->theme         = $this->request->getPost('theme');
            $user->digest        = $this->request->getPost('digest');

            if ($user->save()) {
                $this->session->set('identity-theme', 'D');
                $this->session->get('identity-timezone', $user->timezone);
                $this->flashSession->success('Settings were successfully updated');
                $this->response->redirect();
                return;
            }

        } else {
            $this->tag->displayTo('timezone', $user->timezone);
            $this->tag->displayTo('notifications', $user->notifications);
            $this->tag->displayTo('theme', $user->theme);
            $this->tag->displayTo('digest', $user->digest);
        }

        $this->setUserStats($user);

        $this->view->setVars([
            'user'          => $user,
            'currentTab'    => 'settings'
        ]);
    }

    /**
     * Allow to change your user settings
     */
    public function securityAction()
    {
        $this->tag->setTitle('我的设置');

        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $this->response->redirect();
            return;
        }

        $user = Users::findFirstById($usersId);

        if (!$user) {
            $this->response->redirect();
            return;
        }

        if ($this->request->isPost()) {

            if (!$this->checkTokenPost('settings')) {
                $this->response->redirect();
                return;
            }

            $user->timezone      = $this->request->getPost('timezone');
            $user->notifications = $this->request->getPost('notifications');
            $user->theme         = $this->request->getPost('theme');
            $user->digest        = $this->request->getPost('digest');

            if ($user->save()) {
                $this->session->set('identity-theme', 'D');
                $this->session->get('identity-timezone', $user->timezone);
                $this->flashSession->success('Settings were successfully updated');
                $this->response->redirect();
                return;
            }

        } else {
            $this->tag->displayTo('timezone', $user->timezone);
            $this->tag->displayTo('notifications', $user->notifications);
            $this->tag->displayTo('theme', $user->theme);
            $this->tag->displayTo('digest', $user->digest);
        }

        $this->setUserStats($user);

        $this->view->setVars([
            'user'          => $user,
            'currentTab'    => 'security'
        ]);
    }

    /**
     * Allow to change your user social settings
     */
    public function socialAction()
    {
        $this->tag->setTitle('社交信息');

        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $this->response->redirect();
            return;
        }

        $user = Users::findFirstById($usersId);

        if (!$user) {
            $this->response->redirect();
            return;
        }

        $this->setUserStats($user);

        $form = new SocialForm();
        $this->view->form = $form;

        $this->view->setVars([
            'user'          => $user,
            'currentTab'    => 'social'
        ]);
    }

    /**
     * Users must use this action to change its social info
     */
    public function updateSocialAction()
    {
        $response = new Response();

        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $contentlogIn = [
                'status'  => 'error',
                'message' => 'You must log in first to vote'
            ];
            return $response->setJsonContent($contentlogIn);
        }

        if ($this->request->isPost()) {

            $column = $this->request->getPost('id');
            $value = $this->request->getPost('content');

            $socialInfo = UsersSocial::findFirstByUsersId($usersId);
            if (!$socialInfo) {
                $socialInfo = new UsersSocial();
                $socialInfo->users_id = $usersId;
            }

            if ($column == 'a') {
                $socialInfo->gender = $value;
            } else if($column == 'b') {
                $socialInfo->city = $value;
            } else if($column == 'c') {
                $socialInfo->skills = $value;
            } else if($column == 'd') {
                $socialInfo->github = $value;
            } else if($column == 'e') {
                $socialInfo->weibo = $value;
            } else if($column == 'f') {
                $socialInfo->website = $value;
            } else if($column == 'g') {
                $socialInfo->gzhao = $value;
            } else if($column == 'h') {
                $socialInfo->zhihu = $value;
            }

            if (!$socialInfo->save()) {

                $contentOk = [
                    'status'  => 'error',
                    'message' => '保存出错，请重试'
                ];

                return $response->setJsonContent($contentOk);
            }
        }

        $contentOk = [
            'status' => 'OK'
        ];

        return $response->setJsonContent($contentOk);
    }

    /**
     * Users must use this action to change its password
     */
    public function changePasswordAction()
    {
        $this->tag->setTitle('修改密码');

        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $this->response->redirect();
            return;
        }

        $user = Users::findFirstById($usersId);

        if (!$user) {
            $this->response->redirect();
            return;
        }

        $form = new ChangePasswordForm();

        if ($this->request->isPost()) {

            if (!$form->isValid($this->request->getPost())) {

                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {

                $user = $this->auth->getUser();

                $user->password = $this->security->hash($this->request->getPost('password'));

                $passwordChange = new ChangesPassword();
                $passwordChange->user = $user;
                $passwordChange->ipAddress = $this->request->getClientAddress();
                $passwordChange->userAgent = $this->request->getUserAgent();

                if (!$passwordChange->save()) {
                    $this->flash->error($passwordChange->getMessages());
                } else {

                    $this->flash->success('密码修改成功');

                    Tag::resetInput();
                }
            }
        }

        $this->setUserStats($user);

        $this->view->form = $form;

        $this->view->setVars([
            'user' => $user
        ]);
    }

    /**
     * Users must use this action to change its domain
     */
    public function changeDomainAction()
    {
        $this->tag->setTitle('修改个人域名');

        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $this->response->redirect();
            return;
        }

        $user = Users::findFirstById($usersId);

        if (!$user) {
            $this->flashSession->error('The user does not exist');
            return $this->response->redirect();
        }

        $form = new ChangeDomainForm();
        $change = ChangesLogin::findFirstByUsersId($user->id);

        if ($this->request->isPost()) {

            if (!$form->isValid($this->request->getPost())) {

                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {

                $login = $this->request->getPost('login');

                $passValidate = true;

                // Can't contain non english characters
                if (preg_match("/[^a-zA-Z0-9-]/i", $login)) {
                    $this->flash->error('不能使用英文字母、数字和-之外的字符');
                    $passValidate = false;
                }

                if ($passValidate) {
                    $user->login = $login;

                    if (!$change) {
                        $loginChange = new ChangesLogin();
                        $loginChange->user = $user;
                        $loginChange->ipAddress = $this->request->getClientAddress();
                        $loginChange->userAgent = $this->request->getUserAgent();
                        $loginChange->modifiedAt = time();
                    } else {
                        $loginChange = $change;
                        $loginChange->user = $user;
                    }

                    if (!$loginChange->save()) {
                        $this->flash->error($loginChange->getMessages());
                    } else {
                        $this->session->set('identity-login', $user->login);
                        $this->flashSession->success('域名修改成功');
                        return $this->response->redirect('account/changeDomain');
                    }
                }
            }
        }

        if ($change) {
            $days = HumanTime::diffDays($change->modifiedAt);
        } else {
            $days = -1;
        }

        $this->setUserStats($user);

        $this->view->setVars([
            'user'          => $user,
            'days'          => $days
        ]);

        $this->view->form = $form;
    }

    /**
     * Users must use this action to change its name
     */
    public function changeNameAction()
    {
        $this->tag->setTitle('修改姓名');

        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $this->response->redirect();
            return;
        }

        $user = Users::findFirstById($usersId);

        if (!$user) {
            $this->flashSession->error('The user does not exist');
            return $this->response->redirect();
        }

        $form = new ChangeNameForm();

        $change = ChangesNickname::findFirstByUsersId($user->id);

        if ($this->request->isPost()) {

            if (!$form->isValid($this->request->getPost())) {

                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {

                $user->name = $this->request->getPost('name');

                if (!$change) {
                    $nicknameChange = new ChangesNickname();
                    $nicknameChange->user = $user;
                    $nicknameChange->ipAddress = $this->request->getClientAddress();
                    $nicknameChange->userAgent = $this->request->getUserAgent();
                    $nicknameChange->modifiedAt = time();
                } else {
                    $nicknameChange = $change;
                    $nicknameChange->user = $user;
                }

                if (!$nicknameChange->save()) {
                    $this->flash->error($nicknameChange->getMessages());
                } else {
                    $this->session->set('identity-name', $user->name);
                    $this->flashSession->success('姓名修改成功');
                    return $this->response->redirect('account/changeName');
                }
            }
        }

        if ($change) {
            $days = HumanTime::diffDays($change->modifiedAt);
        } else {
            $days = -1;
        }

        $this->setUserStats($user);

        $this->view->setVars([
            'user'          => $user,
            'days'          => $days
        ]);

        $this->view->form = $form;
    }

    /**
     * Users must use this action to change its city
     */
    public function changeCityAction()
    {
        $this->tag->setTitle('修改城市');

        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $this->flashSession->error('你尚未登录');
            $this->response->redirect();
            return;
        }

        $user = Users::findFirstById($usersId);

        if (!$user) {
            $this->flashSession->error('用户不存在');
            return $this->response->redirect();
        }

        $form = new ChangeCityForm();

        if ($this->request->isPost()) {

            if (!$form->isValid($this->request->getPost())) {

                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }

            } else {

                $newCity = $this->request->getPost('city');

                $city = Cities::findFirstByName($newCity);

                if (!$city) {
                    $city = new Cities();
                    $city->name = $newCity;
                    $city->slug = $newCity;
                    $city->number_users = 1;
                } else {
                    $city->number_users += 1;
                }

                if ($city->save()) {
                    $user->city_id = $city->id;
                    $user->city_name = $city->name;
                    $user->save();
                }

                $this->flashSession->success('城市修改成功');
                return $this->response->redirect('account/changeCity');
            }
        }

        $this->view->setVars([
            'user'          => $user
        ]);
        $this->view->form = $form;
    }

    /**
     * Shows the latest notifications for the current user
     */
    public function notificationsAction($offset = 0)
    {
        $this->tag->setTitle('我的消息');

        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $this->flashSession->error('You must be logged first');
            return $this->response->redirect();
        }

        $user = Users::findFirstById($usersId);

        if (!$user) {
            $this->flashSession->error('The user does not exist');
            return $this->response->redirect();
        }

        $this->view->notifications = ActivityNotifications::find([
            'users_id = ?0',
            'bind'  => [$usersId],
            'limit' => 128,
            'order' => 'created_at DESC'
        ]);

        $this->setUserStats($user);

        $this->view->setVars([
            'currentTab'    => 'activity',
            'user'          => $user
        ]);
    }

    public function sendMessageAction()
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

        // if (!$this->checkTokenGetJson('user-' . $user->id)) {
        //     $csrfTokenError = [
        //         'status'  => 'error',
        //         'message' => '页面已失效，请刷新'
        //     ];
        //     return $response->setJsonContent($csrfTokenError);
        // }

        $content = $this->request->getPost('message');

        $sendMessage = new MessagesOutbox();
        $sendMessage->users_id = $this->myself->id;
        $sendMessage->users_receive_id = $toUserId;
        $sendMessage->content = $content;

        $receiveMessage = new MessagesInbox();
        $receiveMessage->users_id = $toUserId;
        $receiveMessage->users_origin_id = $this->myself->id;
        $receiveMessage->content = $content;

        $title = null;

        if (mb_strlen($content) > 15) {
            $title = mb_substr($content, 0, 15) . '...';
        } else {
            $title = $content;
        }

        if ($sendMessage->save() && $receiveMessage->save()) {

            $activity                    = new ActivityNotifications();
            $activity->users_id          = $toUser->id;
            $activity->users_origin_id   = $user->id;
            $activity->type              = ActivityNotifications::GOT_MESSAGE;
            $activity->extra             = $title;
            $activity->save();

            $this->flashSession->success('发送成功');
            return $response->setJsonContent(['status' => 'OK']);
        } else {
            $error = [
                'status'  => 'error',
                'message' => '发送失败，请重试'
            ];
            return $response->setJsonContent($error);
        }
    }

    /**
     * Deletes a message
     *
     * @param int $id
     *
     * @return Response
     */
    public function deleteMessageAction($id)
    {
        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $this->flashSession->error('你尚未登录');
            return $this->response->redirect();
        }

        if (!$user = Users::findFirstById($usersId)) {
            $this->flashSession->error('用户不存在');
            return $this->response->redirect();
        }

        $type = $this->request->get('type');

        $parameters = [
            'id = ?0 AND users_id = ?1',
            'bind' => [$id, $usersId]
        ];

        $message = null;

        if ($type == 'in') {

            $message = MessagesInbox::findFirst($parameters);

            if ($message && $message->delete()) {
                $this->flashSession->success('删除成功');
            }

            return $this->response->redirect('/inbox');

        } else if($type == 'out') {

            $message = MessagesOutbox::findFirst($parameters);

            if ($message && $message->delete()) {
                $this->flashSession->success('删除成功');
            }

            return $this->response->redirect('/outbox');
        }
    }


    /**
     * Shows the messages inbox for the current user
     */
    public function messagesAction($tab = 'inbox', $offset = 0)
    {
        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $this->flashSession->error('你尚未登录');
            return $this->response->redirect();
        }

        $user = Users::findFirstById($usersId);

        if (!$user) {
            $this->flashSession->error('用户不存在');
            return $this->response->redirect();
        }

        if ($tab == 'inbox') {

            $this->tag->setTitle('收件箱');

            $this->view->messages = MessagesInbox::find([
                'users_id = ?0',
                'bind'  => [$usersId],
                'limit' => 50,
                'order' => 'created_at DESC'
            ]);
        } elseif($tab == 'outbox') {

            $this->tag->setTitle('发件箱');

            $this->view->messages = MessagesOutbox::find([
                'users_id = ?0',
                'bind'  => [$usersId],
                'limit' => 50,
                'order' => 'created_at DESC'
            ]);
        }

        $this->setUserStats($user);

        $this->view->setVars([
            'currentTab'    => $tab,
            'user'          => $user
        ]);
    }

    /**
     * Follow the user
     *
     * @param string $id
     * @return ResponseInterface
     */
    public function followAction($uid)
    {
        $response = new Response();

        $myself = $this->myself;

        if (!$myself) {
            $contentlogIn = [
                'status'  => 'error',
                'message' => 'You must log in first'
            ];
            return $response->setJsonContent($contentlogIn);
        }

        if ($myself->id == $uid) {
            $contentlogIn = [
                'status'  => 'error',
                'message' => 'You can\'t follow yourself'
            ];
            return $response->setJsonContent($contentlogIn);
        }

        $followWho = Users::findFirstById($uid);
        if (!$followWho) {
            $contentNotExist = [
                'status'  => 'error',
                'message' => 'User does not exist'
            ];
            return $response->setJsonContent($contentNotExist);
        }

        $userFollow = UsersFollowers::findFirst(
            ["conditions" => "users_id = ?1 AND followers_id = ?2",
             "bind" => ['1' => $uid, '2' => $myself->id]]
        );

        if ($userFollow) {
            $alreadyFollow = [
                'status'  => 'error',
                'message' => '你已经关注此人'
            ];
            return $response->setJsonContent($alreadyFollow);
        }

        $userFollow           = new UsersFollowers();
        $userFollow->users_id = $uid;
        $userFollow->followers_id = $myself->id;

        if (!$userFollow->save()) {
            foreach ($userFollow->getMessages() as $message) {
                $contentError = [
                    'status'  => 'error',
                    'message' => (string) $message->getMessage()
                ];
                return $response->setJsonContent($contentError);
            }
        } else {

            $followWho->number_followers += 1;
            $followWho->save();

            $myself->number_followings += 1;
            $myself->save();

            $activityNotification                    = new ActivityNotifications();
            $activityNotification->users_id          = $followWho->id;
            $activityNotification->users_origin_id   = $myself->id;
            $activityNotification->type              = ActivityNotifications::USER_FOLLOW;
            $activityNotification->save();

            $activity                 = new Activities();
            $activity->users_id       = $myself->id;
            $activity->follow_user_id = $followWho->id;
            $activity->type           = Activities::FOLLOW_USER;
            $activity->save();
        }

        $contentOk = [
            'status' => 'OK'
        ];

        return $response->setJsonContent($contentOk);
    }

    /**
     * Unfollow a user
     *
     * @param string $id
     * @return ResponseInterface
     */
    public function unfollowAction($uid)
    {
        $response = new Response();

        $myself = $this->myself;

        if (!$myself) {
            $contentlogIn = [
                'status'  => 'error',
                'message' => 'You must log in first'
            ];
            return $response->setJsonContent($contentlogIn);
        }

        $unfollowWho = Users::findFirstById($uid);
        if (!$unfollowWho) {
            $contentNotExist = [
                'status'  => 'error',
                'message' => 'User does not exist'
            ];
            return $response->setJsonContent($contentNotExist);
        }

        $userFollow = UsersFollowers::findFirst(
            ["conditions" => "users_id = ?1 AND followers_id = ?2",
             "bind" => ['1' => $uid, '2' => $myself->id]]
        );

        if ($userFollow == null) {
            $contentAlreadyVote = [
                'status'  => 'error',
                'message' => '你没有关注此人'
            ];
            return $response->setJsonContent($contentAlreadyVote);
        }

        if ($userFollow && !$userFollow->delete()) {
            foreach ($userFollow->getMessages() as $message) {
                $contentError = [
                    'status'  => 'error',
                    'message' => (string) $message->getMessage()
                ];
                return $response->setJsonContent($contentError);
            }
        } else {
            if ($unfollowWho->number_followers >= 1) {
                $unfollowWho->number_followers -= 1;
                $unfollowWho->save();
            }

            if ($myself->number_followings >= 1) {
                $myself->number_followings -= 1;
                $myself->save();
            }
        }

        $contentOk = [
            'status' => 'OK'
        ];

        return $response->setJsonContent($contentOk);
    }

    public function walletAction($tab = 'home')
    {
        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $this->response->redirect();
            return;
        }

        $user = Users::findFirstById($usersId);

        if (!$user) {
            $this->response->redirect();
            return;
        }

        $moneyLog = UsersBankbook::find(
            ["users_id = ?1", 'bind' => [1 => $usersId],
             "order" => "created_at DESC", "limit" => 50]);

        $this->setUserStats($user);

        $this->tag->setTitle('我的设置');

        $this->view->setVars([
            'user'          => $user,
            'currentTab'    => $tab,
            'moneyLog'      => $moneyLog
        ]);
    }

    public function inviteAction($tab = 'home')
    {
        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $this->response->redirect();
            return;
        }

        $user = Users::findFirstById($usersId);

        if (!$user) {
            $this->response->redirect();
            return;
        }

        $myCodes = UsersCodes::find(
            ["users_id = ?1", 'bind' => [1 => $usersId],
             "order" => "used DESC, created_at DESC", "limit" => 10]);

        if (!$myCodes) {
            UsersCodes::generateCodes($user, 5);

            $myCodes = UsersCodes::find(
                ["users_id = ?1", 'bind' => [1 => $usersId],
                 "order" => "created_at DESC", "limit" => 10]);
        }

        $this->setUserStats($user);

        $this->tag->setTitle('我的邀请码');

        $this->view->setVars([
            'user'          => $user,
            'currentTab'    => $tab,
            'myCodes'       => $myCodes
        ]);
    }

    private function setUserStats($user)
    {
        $parametersNumberPosts = [
            'users_id = ?0 AND deleted = 0',
            'bind' => [$user->id]
        ];
        $numberPosts = Posts::count($parametersNumberPosts);

        $parametersNumberReplies = [
            'users_id = ?0',
            'bind' => [$user->id]
        ];
        $numberReplies = PostsReplies::count($parametersNumberReplies);

        $socialInfo = UsersSocial::getInfoById($usersId);

        $this->view->setVars([
            'numberPosts'   => $numberPosts,
            'numberReplies' => $numberReplies,
            'showSocial'    => $socialInfo[0],
            'socialData'    => $socialInfo[1]
        ]);
    }
}
