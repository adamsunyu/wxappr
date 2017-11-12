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

use Phalcon\Mvc\Router;
use Phosphorum\Http\Filter\Ajax;

$router = new Router(false);
$router->removeExtraSlashes(true);

$router->add(
    '/sitemap.xml',
    [
       'controller' => 'sitemap',
       'action'     => 'index'
    ]
);

$router->add(
    '/robots.txt',
    [
        'controller' => 'robots',
        'action'     => 'index'
    ]
);

$router->add(
    '/help/([a-zA-Z\-]+)',
    [
       'controller' => 'help',
       'action'     => 1
    ]
);

$router->add(
    '/hook/mail-bounce',
    [
       'controller' => 'hooks',
       'action'     => 'mailBounce'
    ]
);

$router->add(
    '/hook/mail-reply',
    [
       'controller' => 'hooks',
       'action'     => 'mailReply'
    ]
);

$router->add(
    '/search',
    [
       'controller' => 'discussions',
       'action'     => 'search'
    ]
);

$router->addPost(
    '/preview',
    [
       'controller' => 'utils',
       'action'     => 'preview'
    ]
);

$router->add(
    '/reply/accept/{id:[0-9]+}',
    [
       'controller' => 'replies',
       'action'     => 'accept'
    ]
);

$router->add(
    '/reply/vote-up/{id:[0-9]+}',
    [
       'controller' => 'replies',
       'action'     => 'voteUp'
    ]
);

$router->add(
    '/reply/vote-down/{id:[0-9]+}',
    [
       'controller' => 'replies',
       'action'     => 'voteDown'
    ]
);

$router->add(
    '/reply/history/{id:[0-9]+}',
    [
       'controller' => 'replies',
       'action'     => 'history'
    ]
)->beforeMatch([new Ajax, 'check']);

$router->add(
    '/post/history/{id:[0-9]+}',
    [
       'controller' => 'discussions',
       'action'     => 'history'
    ]
)->beforeMatch([new Ajax, 'check']);

$router->add(
    '/post/vote/{id:[0-9]+}/{type:[0-9]+}',
    [
       'controller' => 'discussions',
       'action'     => 'vote'
    ]
);

$router->add(
    '/poll/vote/{id:[0-9]+}/{option:[0-9]+}',
    [
        'controller' => 'polls',
        'action'     => 'vote'
    ]
);

$router->add(
    '/post/vote-down/{id:[0-9]+}',
    [
       'controller' => 'discussions',
       'action'     => 'voteDown'
    ]
);

$router->add(
    '/login/oauth/authorize',
    [
       'controller' => 'session',
       'action'     => 'authorize'
    ]
);

$router->add(
    '/login/oauth/access_token/',
    [
       'controller' => 'session',
       'action'     => 'accessToken'
    ]
);

$router->add(
    '/login/oauth/access_token',
    [
       'controller' => 'session',
       'action'     => 'accessToken'
    ]
);

$router->add(
    '/find-related',
    [
       'controller' => 'discussions',
       'action'     => 'findRelated'
    ]
);

$router->add(
    '/show-related',
    [
       'controller' => 'discussions',
       'action'     => 'showRelated'
    ]
);

$router->add(
    '/notifications',
    [
       'controller' => 'users',
       'action'     => 'notifications'
    ]
);

$router->add(
    '/activity',
    [
       'controller' => 'discussions',
       'action'     => 'activity'
    ]
);

$router->add(
    '/delete/post/{id:[0-9]+}',
    [
       'controller' => 'discussions',
       'action'     => 'delete'
    ]
);

$router->add(
    '/new',
    [
       'controller' => 'discussions',
       'action'     => 'post'
    ]
);

$router->add(
    '/new/{category}',
    [
       'controller' => 'discussions',
       'action'     => 'post'
    ]
);

$router->add(
    '/new/link',
    [
       'controller' => 'discussions',
       'action'     => 'postLink'
    ]
);

$router->add(
    '/edit/post/{id:[0-9]+}',
    [
       'controller' => 'discussions',
       'action'     => 'edit'
    ]
);

$router->add(
    '/edit/link/{id:[0-9]+}',
    [
       'controller' => 'discussions',
       'action'     => 'editLink'
    ]
);

$router->add(
    '/stick/post/{id:[0-9]+}',
    [
        'controller' => 'discussions',
        'action'     => 'stick'
    ]
);

$router->add(
    '/unstick/post/{id:[0-9]+}',
    [
        'controller' => 'discussions',
        'action'     => 'unstick'
    ]
);

$router->add(
    '/subscribe/post/{id:[0-9]+}',
    [
       'controller' => 'discussions',
       'action'     => 'subscribe'
    ]
);

$router->add(
    '/unsubscribe/post/{id:[0-9]+}',
    [
       'controller' => 'discussions',
       'action'     => 'unsubscribe'
    ]
);

$router->add(
    '/ranks',
    [
        'controller' => 'ranks',
        'action'     => 'index'
    ]
);

$router->add(
    '/ranks/{tab}',
    [
        'controller' => 'ranks',
        'action'     => 'index'
    ]
);

$router->add(
    '/user/{login}',
    [
       'controller' => 'users',
       'action'     => 'view'
    ]
);

$router->add(
    '/user/{login}/{tab}',
    [
       'controller' => 'users',
       'action'     => 'view'
    ]
);

$router->add(
    '/user-follow/{id}',
    [
       'controller' => 'users',
       'action'     => 'follow'
    ]
);

$router->add(
    '/user-unfollow/{id}',
    [
       'controller' => 'users',
       'action'     => 'unfollow'
    ]
);

$router->add(
    '/reply/{id:[0-9]+}',
    [
       'controller' => 'replies',
       'action'     => 'get'
    ]
);

$router->add(
    '/reply/update',
    [
       'controller' => 'replies',
       'action'     => 'update'
    ]
);

$router->add(
    '/reply/delete/{id:[0-9]+}',
    [
       'controller' => 'replies',
       'action'     => 'delete'
    ]
);

$router->add(
    '/account/signup',
    [
       'controller' => 'session',
       'action'     => 'signup'
    ]
);

$router->add(
    '/account/welcome',
    [
       'controller' => 'session',
       'action'     => 'verifyEmail'
    ]
);

$router->add(
    '/account/login',
    [
       'controller' => 'session',
       'action'     => 'login'
    ]
);

$router->add(
    '/account/activate',
    [
       'controller' => 'session',
       'action'     => 'activate'
    ]
);

$router->add(
    '/account/forgotPassword',
    [
       'controller' => 'session',
       'action'     => 'forgotPassword'
    ]
);

$router->add(
    '/confirm/{code}',
    [
        'controller' => 'session',
        'action' => 'signup'
    ]
);

$router->add(
    '/reset-password/{code}/{email}',
    [
        'controller' => 'session',
        'action' => 'resetPassword'
    ]
);

$router->add(
    '/account/changePassword',
    [
       'controller' => 'users',
       'action'     => 'changePassword'
    ]
);

$router->add(
    '/account/changeName',
    [
       'controller' => 'users',
       'action'     => 'changeName'
    ]
);

$router->add(
    '/account/changeCity',
    [
       'controller' => 'users',
       'action'     => 'changeCity'
    ]
);

$router->add(
    '/account/changeDomain',
    [
       'controller' => 'users',
       'action'     => 'changeDomain'
    ]
);

$router->add(
    '/account/settings',
    [
        'controller' => 'users',
        'action'     => 'settings'
    ]
);

$router->add(
    '/account/security',
    [
        'controller' => 'users',
        'action'     => 'security'
    ]
);

$router->add(
    '/account/social',
    [
        'controller' => 'users',
        'action'     => 'social'
    ]
);

$router->add(
    '/account/social-update',
    [
       'controller' => 'users',
       'action'     => 'updateSocial'
    ]
)->beforeMatch([new Ajax, 'check']);

$router->add(
    '/account/upload',
    [
        'controller' => 'users',
        'action'     => 'upload'
    ]
);

$router->add(
    '/invite/{tab}',
    [
        'controller' => 'users',
        'action'     => 'invite'
    ]
);

$router->add(
    '/join/{code}',
    [
        'controller' => 'session',
        'action'     => 'signup'
    ]
);

$router->add(
    '/account/logout',
    [
       'controller' => 'session',
       'action'     => 'logout'
    ]
);

$router->add(
    '/about/agreement',
    [
       'controller' => 'help',
       'action'     => 'agreement'
    ]
);
$router->add(
    '/about/guideline',
    [
       'controller' => 'help',
       'action'     => 'guideline'
    ]
);
$router->add(
    '/about/privacy',
    [
       'controller' => 'help',
       'action'     => 'privacy'
    ]
);

$router->add(
    '/about/disclaimer',
    [
       'controller' => 'help',
       'action'     => 'disclaimer'
    ]
);

$router->add(
    '/bbs',
    [
       'controller' => 'discussions',
       'action'     => 'index'
    ]
);

$router->add(
    '/bbs/{tab}',
    [
       'controller' => 'discussions',
       'action'     => 'index'
    ]
);

$router->add(
    '/bbs/{tab}/{offset}',
    [
       'controller' => 'discussions',
       'action'     => 'index'
    ]
);

$router->add(
    '/topics/{slug}',
    [
       'controller' => 'discussions',
       'action'     => 'index'
    ]
);

$router->add(
    '/topics',
    [
       'controller' => 'discussions',
       'action'     => 'index'
    ]
);

$router->add(
    '/topics/{slug}',
    [
       'controller' => 'discussions',
       'action'     => 'index'
    ]
);

$router->add(
    '/topics/{slug}/{offset:[0-9]+}',
    [
       'controller' => 'discussions',
       'action'     => 'index'
    ]
);

$router->add(
    '/topic/{id:[0-9]+}',
    [
       'controller' => 'discussions',
       'action'     => 'view'
    ]
);

$router->add(
    '/topic/{id:[0-9]+}/{slug}',
    [
       'controller' => 'discussions',
       'action'     => 'view'
    ]
);

$router->add(
    '/mytopic/{id:[0-9]+}',
    [
       'controller' => 'users',
       'action'     => 'mytopic'
    ]
);

$router->add(
    '/image/upload',
    [
       'controller' => 'image',
       'action'     => 'upload'
    ]
);

$router->add(
    '/nodes',
    [
       'controller' => 'nodes',
       'action'     => 'index'
    ]
);

$router->add(
    '/nodes/{slug}',
    [
       'controller' => 'nodes',
       'action'     => 'index'
    ]
);

$router->add(
    '/nodes/{slug}',
    [
       'controller' => 'nodes',
       'action'     => 'index'
    ]
);

$router->add(
    '/nodes/{slug}/{offset}',
    [
       'controller' => 'nodes',
       'action'     => 'view'
    ]
);

$router->add(
    '/node-create',
    [
       'controller' => 'nodes',
       'action'     => 'create'
    ]
);

$router->add(
    '/node-edit/{id}',
    [
       'controller' => 'nodes',
       'action'     => 'edit'
    ]
);

$router->add(
    '/node-icon/{id}',
    [
       'controller' => 'nodes',
       'action'     => 'icon'
    ]
);

$router->add(
    '/node-icon/upload',
    [
        'controller' => 'nodes',
        'action'     => 'uploadIcon'
    ]
);

$router->add(
    '/node-follow/{id}',
    [
       'controller' => 'nodes',
       'action'     => 'follow'
    ]
);

$router->add(
    '/node-unfollow/{id}',
    [
       'controller' => 'nodes',
       'action'     => 'unfollow'
    ]
);

$router->add(
    '/node/{slug}',
    [
       'controller' => 'nodes',
       'action'     => 'view'
    ]
);

$router->add(
    '/node/{slug}/{tab}',
    [
       'controller' => 'nodes',
       'action'     => 'view'
    ]
);

$router->add(
    '/node/{slug}/{tab}/{offset}',
    [
       'controller' => 'nodes',
       'action'     => 'view'
    ]
);

$router->add(
    '/topics',
    [
       'controller' => 'discussions',
       'action'     => 'index',
       'nodeSlug'   => 'topics'
    ]
);

$router->add(
    '/topics/{tab}',
    [
       'controller' => 'discussions',
       'action'     => 'index',
       'nodeSlug'   => 'topics'
    ]
);

$router->add(
    '/topics/{tab}/{offset}',
    [
       'controller' => 'discussions',
       'action'     => 'index',
       'nodeSlug'   => 'topics'
    ]
);

$router->add(
    '/resources',
    [
       'controller' => 'discussions',
       'action'     => 'index',
       'nodeSlug'   => 'resources'
    ]
);

$router->add(
    '/resources/{tab}',
    [
       'controller' => 'discussions',
       'action'     => 'index',
       'nodeSlug'   => 'resources'
    ]
);

$router->add(
    '/resources/{tab}/{offset}',
    [
       'controller' => 'discussions',
       'action'     => 'index',
       'nodeSlug'   => 'resources'
    ]
);

$router->add(
    '/questions',
    [
       'controller' => 'discussions',
       'action'     => 'index',
       'nodeSlug'   => 'questions'
    ]
);

$router->add(
    '/questions/{tab}',
    [
       'controller' => 'discussions',
       'action'     => 'index',
       'nodeSlug'   => 'questions'
    ]
);

$router->add(
    '/questions/{tab}/{offset}',
    [
       'controller' => 'discussions',
       'action'     => 'index',
       'nodeSlug'   => 'questions'
    ]
);

$router->add(
    '/cities',
    [
       'controller' => 'cities',
       'action'     => 'index'
    ]
);

$router->add(
    '/cities/{city}',
    [
       'controller' => 'cities',
       'action'     => 'index'
    ]
);

$router->add(
    '/cities/{city}/{offset}',
    [
       'controller' => 'cities',
       'action'     => 'index'
    ]
);

$router->add(
    '/app/vote/{id:[0-9]+}/{type:[0-9]+}',
    [
       'controller' => 'apps',
       'action'     => 'vote'
    ]
);

$router->add(
    '/app/{appid}',
    [
       'controller' => 'apps',
       'action'     => 'view'
    ]
);

$router->add(
    '/apps',
    [
       'controller' => 'apps',
       'action'     => 'index'
    ]
);

$router->add(
    '/apps/{tag}',
    [
       'controller' => 'apps',
       'action'     => 'index'
    ]
);

$router->add(
    '/apps/{tag}/{offset}',
    [
       'controller' => 'apps',
       'action'     => 'index'
    ]
);

$router->add(
    '/search/app',
    [
       'controller' => 'apps',
       'action'     => 'search'
    ]
);

$router->add(
    '/search/app/{keywords}',
    [
       'controller' => 'apps',
       'action'     => 'search'
    ]
);

$router->add(
    '/edit/app/{id}',
    [
       'controller' => 'apps',
       'action'     => 'edit'
    ]
);

$router->add(
    '/create/app',
    [
       'controller' => 'apps',
       'action'     => 'create'
    ]
);

$router->add(
    '/publish/step2/{appId}',
    [
       'controller' => 'apps',
       'action'     => 'uploadIcons'
    ]
);

$router->add(
    '/publish/step3/{appId}',
    [
       'controller' => 'apps',
       'action'     => 'ready'
    ]
);

$router->add(
    '/appimage/icon/{appid}',
    [
       'controller' => 'image',
       'action'     => 'appImageUpload',
       'imageType'  => 'icon'
    ]
);

$router->add(
    '/appimage/qrcode/{appid}',
    [
       'controller' => 'image',
       'action'     => 'appImageUpload',
       'imageType'  => 'qrcode'
    ]
);

$router->add(
    '/appimage/screenshot/{appid}/{imgId}',
    [
       'controller' => 'image',
       'action'     => 'appImageUpload',
       'imageType'  => 'screenshot'
    ]
);

$router->add(
    '/thankUser',
    [
       'controller' => 'thanks',
       'action'     => 'thank'
    ]
)->beforeMatch([new Ajax, 'check']);

$router->add(
    '/message/send',
    [
       'controller' => 'users',
       'action'     => 'sendMessage'
    ]
)->beforeMatch([new Ajax, 'check']);

$router->add(
    '/message/delete/{id}',
    [
       'controller' => 'users',
       'action'     => 'deleteMessage'
    ]
);

$router->add(
    '/inbox',
    [
       'controller' => 'users',
       'action'     => 'messages',
       'tab'        => 'inbox'
    ]
);

$router->add(
    '/outbox',
    [
       'controller' => 'users',
       'action'     => 'messages',
       'tab'        => 'outbox'
    ]
);

$router->add(
    '/wallet',
    [
       'controller' => 'users',
       'action'     => 'wallet'
    ]
);

$router->add(
    '/wallet/{tab}',
    [
       'controller' => 'users',
       'action'     => 'wallet'
    ]
);

$router->add(
    '/fetch/link',
    [
       'controller' => 'utils',
       'action'     => 'fetchLink'
    ]
)->beforeMatch([new Ajax, 'check']);

$router->add(
    '/',
    [
       'controller' => 'apps',
       'action'     => 'index'
    ]
);

return $router;
