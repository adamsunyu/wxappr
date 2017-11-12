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

use Phosphorum\Github\OAuth;
use Phosphorum\Github\Users as GithubUsers;
use Phosphorum\Models\Users as ForumUsers;
use Phosphorum\Models\NotificationsBounces;
use Phosphorum\Models\ActivityNotifications;
use Phalcon\Mvc\Model;
use Phalcon\Config;
use Phosphorum\Forms\LoginForm;
use Phosphorum\Forms\SignUpForm;
use Phosphorum\Forms\ForgotPasswordForm;
use Phosphorum\Auth\Exception as AuthException;
use Phosphorum\Models\Users;
use Phosphorum\Models\Bank;
use Phosphorum\Models\ResetPasswords;
use Phosphorum\Models\EmailConfirmations;
use Phosphorum\Forms\VerifyForm;
use Phosphorum\Models\Cities;

/**
 * Class SessionController
 *
 * @package Phosphorum\Controllers
 */
class SessionController extends ControllerBase
{
    /**
     * Allow a user to signup to the system
     */
    public function signupAction()
    {
        $code = $this->dispatcher->getParam('code');

        if (!$code) {
            return $this->response->redirect('account/welcome');
        }

        $confirmation = EmailConfirmations::findFirstByCode($code);
        if (!$confirmation) {
            return $this->response->redirect('account/welcome');
        }

        if ($confirmation->confirmed == 'Y') {
            return $this->dispatcher->redirect('account/login');
        }

        $this->tag->setTitle("注册账号");

        $form = new SignUpForm();

        if ($this->request->isPost()) {

            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            }

            if ($form->isValid($this->request->getPost()) != false) {

                $email = $this->request->getPost('email');
                $name = $this->request->getPost('name', 'striptags');
                $cityName = $this->request->getPost('city', 'striptags');
                $name = trim($name);
                $cityName = trim($cityName);

                $passValidate = true;

                if($passValidate && preg_match("/[^a-zA-Z0-9\x{4e00}-\x{9fff}]/iu", $name)) {
                    $this->flash->error('不能使用汉字、英文字母和数字之外的字符');
                    $passValidate = false;
                }

                if ($passValidate) {

                    $parameters = ["conditions" => "alias LIKE '%".$cityName."%'"];
                    $city = Cities::findFirst($parameters);

                    // By default belong to other city
                    $cityId = 7;
                    if (!$city) {
                        $city = new Cities();
                        $city->name = $cityName;
                        $city->slug = $cityName;
                        $city->alias = $cityName;
                        $city->number_users = 1;
                    } else {
                        $city->number_users += 1;

                        if ($city->id >= 7) {
                            $cityId = 7;
                        } else {
                            $cityId = $city->id;
                        }
                    }

                    $city->save();

                    $user = new Users([
                        'name'       => $this->request->getPost('name', 'striptags'),
                        'email'      => $email,
                        'city_id'    => $cityId,
                        'city_name'  => $city->name,
                        'password'   => $this->security->hash($this->request->getPost('password')),
                        'signup_source' => 'E',
                        'active'     => 'Y'
                    ]);

                    if ($user->save()) {

                        // 必须放在update login之前
                        $this->generateActivityNotification($user);

                        // Update the login
                        $prefix = $user->randomLetter();
                        $user->login = $prefix.$user->id;

                        if(!$user->save()) {
                            $prefix = $user->randomLetter();
                            $user->login = $prefix.'_'.$user->id;
                            $user->save();
                        }

                        $this->view->setVar('email', $email);

                        /**
                         * Identify the user in the application
                         */
                        $this->auth->authUserById($user->id);

                        $this->auth->createRememberEnvironment($user);

                        $confirmation->usersId = $user->id;
                        $confirmation->confirmed = 'Y';

                        $confirmation->save();

                        return $this->dispatcher->forward([
                            'controller' => 'discussions',
                            'action' => 'index'
                        ]);
                    }

                    $this->flash->error($user->getMessages());
                }
            }
        }

        $this->tag->displayTo('email', $confirmation->email);

        $this->view->form = $form;
    }

    public function verifyEmailAction()
    {
        $this->tag->setTitle("欢迎注册");

        $form = new VerifyForm();

        if ($this->request->isPost()) {

            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            }

            if ($form->isValid($this->request->getPost()) != false) {

                $email = $this->request->getPost('email');

                $emailConfirmation = new EmailConfirmations();
                $emailConfirmation->email = $email;

                if (!$emailConfirmation->save()) {
                    $this->flash->error($emailConfirmation->getMessages());
                } else {

                    $this->view->setVar('email', $email);

                    return $this->dispatcher->forward([
                        'controller' => 'session',
                        'action' => 'activate'
                    ]);
                }
            }
        }

        $this->view->form = $form;
    }

    public function activateAction()
    {
        $this->tag->setTitle("激活账号");
    }

    /**
     * Starts a session in the admin backend
     */
    public function loginAction()
    {
        $this->tag->setTitle("登录");

        $form = new LoginForm();

        try {

            if (!$this->request->isPost()) {

                if ($this->auth->hasRememberMe()) {
                    return $this->auth->loginWithRememberMe();
                }

            } else {

                if ($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {

                    $this->auth->check([
                        'email' => $this->request->getPost('email'),
                        'password' => $this->request->getPost('password'),
                        'remember' => $this->request->getPost('remember')
                    ]);

                    return $this->response->redirect();
                }
            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        $this->view->form = $form;
    }

    /**
     * Shows the forgot password form
     */
    public function forgotPasswordAction()
    {
        $this->tag->setTitle("重设密码");

        $form = new ForgotPasswordForm();

        if ($this->request->isPost()) {

            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {

                $user = Users::findFirstByEmail($this->request->getPost('email'));

                if (!$user) {
                    $this->flash->success('系统内没有找到这个账号');
                } else {

                    $resetPassword = new ResetPasswords();
                    $resetPassword->usersId = $user->id;

                    if ($resetPassword->save()) {
                        $this->flash->success('已发送一封重置密码邮件，请根据邮件指示重设你的密码');
                    } else {
                        foreach ($resetPassword->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    }
                }
            }
        }

        $this->view->form = $form;
    }

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    protected function indexRedirect()
    {
        return $this->response->redirect();
    }

    /**
     * Returns to the discussion
     *
     * @return \Phalcon\Http\ResponseInterface
     */
    protected function discussionsRedirect()
    {
        $referer = $this->request->getHTTPReferer();
        $path    = parse_url($referer, PHP_URL_PATH);

        if ($path) {

            $this->router->handle($path);

            $finalRedirect = $this->router->wasMatched() ? $this->response->redirect($path, true) : $this->indexRedirect();

            return $finalRedirect;

        } else {
            return $this->indexRedirect();
        }
    }

    /**
     * @return \Phalcon\Http\ResponseInterface|void
     */
    public function authorizeAction()
    {
        if (!$this->session->has('identity')) {
            $oauth = new OAuth($this->config->get('github', new Config));
            return $oauth->authorize();
        }

        return $this->indexRedirect();
    }

    private function generateActivityNotification($user) {

        if ($user->getOperationMade() == Model::OP_CREATE) {
            Bank::handleInitialIncome($user);
        }
    }

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function accessTokenAction()
    {
        $oauth = new OAuth($this->config->get('github', new Config));

        $response = $oauth->accessToken();

        if (is_array($response)) {

            if (isset($response['error'])) {
                $this->flashSession->error('Github: ' . $response['error']);
                return $this->indexRedirect();
            }

            $githubUser = new GithubUsers($response['access_token']);

            if (!$githubUser->isValid()) {
                $this->flashSession->error('Github返回信息错误，无法使用Github登录');
                return $this->indexRedirect();
            }

            $userLogin = $githubUser->getLogin();

            if (empty($userLogin)) {
                $this->flashSession->error(
                    'Github返回信息错误，无法使用Github登录'
                );
                return $this->indexRedirect();
            }

            $emailInfo = $githubUser->getEmail();

            $githubEmail = '';
            if (is_string($emailInfo)) {
                $githubEmail = $emailInfo;
            } elseif (is_array($emailInfo) && isset($emailInfo['email'])) {
                $githubEmail = $emailInfo['email'];
            }

            if ($githubEmail) {
                if (false !== strpos($githubEmail, '@users.noreply.github.com')) {
                    $this->flashSession->error('你的Github隐私设置导致不能得到你的Github Email, 因此无法使用Github登录');
                    return $this->indexRedirect();
                }
            } else {
                $messageCantSend = "没有获取到你的Github Email，无法使用Github登录";
                $this->flashSession->error($messageCantSend);
                return $this->indexRedirect();
            }

            $user = ForumUsers::findFirst(
                [
                    'conditions' => 'github_login = :github_login: AND signup_source = :signup_source:',
                    'bind'       => [
                        'github_login'  => $userLogin,
                        'signup_source' => 'G'
                    ],
                ]
            );

            if ($user == false) {

                $newUser               = new ForumUsers();
                $newUser->name = $githubUser->getName();
                $newUser->github_login = $githubUser->getLogin();
                $newUser->email = $githubEmail;

                $newUser->token_type   = $response['token_type'];
                $newUser->access_token = $response['access_token'];
                $newUser->signup_source = 'G';
                $newUser->active = 'Y';
                $newUser->money = Money::NUM_INITIAL_INCOME;

                if ($newUser->save()) {

                    // 必须放在update login之前
                    $this->generateActivityNotification($newUser);

                    // generate unique login
                    $prefix = $newUser->randomLetter();
                    $newUser->login = $prefix.$newUser->id;

                    if (!$newUser->save()) {

                        // 如果失败，加上_总可以成功吧，如果还失败，那就放弃
                        $newUser->login = $prefix.'_'.$newUser->id;
                        $newUser->save();

                        if (!$newUser->save()) {
                            foreach ($newUser->getMessages() as $message) {
                                $this->flashSession->error((string)$message);
                                return $this->indexRedirect();
                            }
                        }
                    }

                    // Update session
                    $this->session->regenerateId(true);
                    $this->auth->createRememberEnvironment($newUser);
                    $this->auth->setSession($newUser);

                    $this->flashSession->success('欢迎你，' . $newUser->name);

                } else {
                    foreach ($newUser->getMessages() as $message) {
                        $this->flashSession->error((string)$message);
                        return $this->indexRedirect();
                    }
                }
            } else {

                if ($user->banned == 'Y') {
                    $this->flashSession->error('你的账号已经被禁止访问本社区.');
                    return $this->indexRedirect();
                }

                $user->email = $githubEmail;

                if ($user->save()) {

                    // Update session
                    $this->session->regenerateId(true);
                    $this->auth->createRememberEnvironment($user);
                    $this->auth->setSession($user);

                    $this->flashSession->success('欢迎回来，' . $user->name);
                } else {
                    foreach ($newUser->getMessages() as $message) {
                        $this->flashSession->error((string)$message);
                        return $this->indexRedirect();
                    }
                }
            }

            return $this->indexRedirect();
        }

        $this->flashSession->error('Github返回信息错误，请重试');
        return $this->indexRedirect();
    }

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    public function logoutAction()
    {
        $this->auth->remove();

        return  $this->indexRedirect();
    }

    /**
     * Confirms an e-mail, if the user must change thier password then changes it
     */
    public function confirmEmailAction()
    {
        $code = $this->dispatcher->getParam('code');

        $confirmation = EmailConfirmations::findFirstByCode($code);

        if (!$confirmation) {
            return $this->response->redirect();
        }

        if ($confirmation->confirmed != 'N') {
            return $this->dispatcher->forward([
                'controller' => 'session',
                'action' => 'signup'
            ]);
        } else {
            return $this->dispatcher->forward([
                'controller' => 'session',
                'action' => 'login'
            ]);
        }
    }

    public function resetPasswordAction()
    {
        $code = $this->dispatcher->getParam('code');

        $resetPassword = ResetPasswords::findFirstByCode($code);

        if (!$resetPassword) {
            return $this->response->redirect('/');
        }

        if ($resetPassword->reset == 'Y') {

            $this->flash->error('此重置密码邮件已失效');

            return $this->dispatcher->forward([
                'controller' => 'session',
                'action' => 'forgotPassword'
            ]);
        }

        $resetPassword->reset = 'Y';

        /**
         * Change the confirmation to 'reset'
         */
        if (!$resetPassword->save()) {

            $this->flash->error('数据库错误，请稍后重试');

            return $this->dispatcher->forward([
                'controller' => 'session',
                'action' => 'forgotPassword'
            ]);
        }

        /**
         * Identify the user in the application
         */
        $this->auth->authUserById($resetPassword->usersId);
        $this->auth->createRememberEnvironment($resetPassword->user);

        $this->flash->success('请重新设置你的密码');

        return $this->dispatcher->forward([
            'controller' => 'users',
            'action' => 'changePassword'
        ]);
    }
}
