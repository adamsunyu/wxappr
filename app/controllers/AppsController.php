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
use Phosphorum\Models\Apps;
use Phosphorum\Models\AppsTags;
use Phosphorum\Models\AppsVotes;
use Phosphorum\Models\AppsReviews;
use Phosphorum\Utils\TokenTrait;
use Phosphorum\Forms\AppCreateForm;
use Phosphorum\Forms\AppEditForm;
use Phosphorum\Models\AppsViews;

use Phalcon\Tag;

use DateTime;
use DateInterval;

/**
 * Class AppsController
 *
 * @package Phosphorum\Controllers
 */
class AppsController extends ControllerBase
{
    use TokenTrait;

    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Shows apps by tag id
     *
     * @param string $slug
     * @param int  $offset
     */
    public function indexAction($tagId = '1', $offset = 0)
    {
        $itemBuilder = $this
            ->modelsManager
            ->createBuilder()
            ->from(['a' => 'Phosphorum\Models\Apps']);

        $totalBuilder = $this
            ->modelsManager
            ->createBuilder()
            ->from(['a' => 'Phosphorum\Models\Apps']);

        $itemBuilder
            ->columns(['a.*'])
            ->limit(self::POSTS_IN_PAGE);

        $totalBuilder
            ->columns('COUNT(*) AS count');

        if ($offset > 0) {
            $itemBuilder->offset((int)$offset);
        }

        if ($tagId != 'rank') {
            $itemBuilder->where('tag1_id = ' . $tagId . ' OR tag2_id = ' . $tagId . ' OR tag3_id = ' . $tagId);
            $totalBuilder->where('tag1_id = ' . $tagId . ' OR tag2_id = ' . $tagId . ' OR tag3_id = ' . $tagId);
        }

        $itemBuilder->andWhere("status = 'P'");
        $totalBuilder->andWhere("status = 'P'");

        $itemBuilder->orderBy("votes_up DESC");

        $appList = $itemBuilder->getQuery()->execute();
        $number = $totalBuilder->getQuery()->setUniqueRow(true)->execute();
        $totalApps = $number->count;

        $parameters = ["limit" => 9, "columns" => "id, name, slug, number_apps", "order" => "number_apps DESC"];
        $tagList = AppsTags::find($parameters);

        $appTag = null;

        if ($tagId != 'rank') {
            $appTag = AppsTags::findFirstById($tagId);
        }

        $this->tag->setTitle($appTag->name . '小程序');

        $parameters = ["limit" => 5, "order" => "votes_up DESC"];
        $hotApps = Apps::find($parameters);

        $parameters = ["limit" => 5, "order" => "created_at DESC"];
        $newApps = Apps::find($parameters);

        $appCount = Apps::count();

        $this->view->setVars([
            'appList'      => $appList,
            'currentTag'   => $appTag,
            'tagList'      => $tagList,
            'totalApps'    => $totalApps,
            'appTagId'     => $tagId,
            'appTag'       => $appTag,
            'hotApps'      => $hotApps,
            'newApps'      => $newApps,
            'offset'       => $offset,
            'appCount'     => $appCount,
            'paginatorUri' => "apps/{$tagId}"
        ]);
    }

    /**
     * Search the apps
     *
     * @param string $slug
     * @param int  $offset
     */
    public function searchAction($keywords = '')
    {
        $itemBuilder = $this
            ->modelsManager
            ->createBuilder()
            ->from(['a' => 'Phosphorum\Models\Apps']);

        $totalBuilder = $this
            ->modelsManager
            ->createBuilder()
            ->from(['a' => 'Phosphorum\Models\Apps']);

        $itemBuilder
            ->columns(['a.*'])
            ->limit(self::POSTS_IN_PAGE);

        $totalBuilder
            ->columns('COUNT(*) AS count');

        if ($offset > 0) {
            $itemBuilder->offset((int)$offset);
        }

        if ($this->request->isPost()) {
            $keywords = $this->request->getPost('keywords', 'trim');

            if ($keywords != null) {
                $itemBuilder->where("name like '%$keywords%'");
                $totalBuilder->where("name like '%$keywords%'");
            }
        } else if($keywords != null) {

            $keywords = urldecode($keywords);

            $itemBuilder->where("name like '%$keywords%'");
            $totalBuilder->where("name like '%$keywords%'");
        }

        $itemBuilder->andWhere("status = 'P'");
        $totalBuilder->andWhere("status = 'P'");

        $itemBuilder->orderBy("votes_up DESC");

        $appList = $itemBuilder->getQuery()->execute();
        $number = $totalBuilder->getQuery()->setUniqueRow(true)->execute();
        $totalApps = $number->count;

        parent::initPublicSidebar();

        $this->tag->setTitle('搜索' . $keywords . '相关小程序');

        $appCount = Apps::count();

        $parameters = ["limit" => 9, "columns" => "id, name, slug, number_apps", "order" => "number_apps DESC"];
        $tagList = AppsTags::find($parameters);

        $parameters = ["limit" => 5, "order" => "votes_up DESC"];
        $hotApps = Apps::find($parameters);

        $parameters = ["limit" => 5, "order" => "created_at DESC"];
        $newApps = Apps::find($parameters);

        $this->view->setVars([
            'appList'      => $appList,
            'tag'          => $appTag,
            'tagList'      => $tagList,
            'totalApps'    => $totalApps,
            'appTag'       => $appTag,
            'currentTab'   => $tagSlug,
            'hotApps'      => $hotApps,
            'newApps'      => $newApps,
            'offset'       => $offset,
            'appCount'     => $appCount,
            'keywords'     => $keywords,
            'paginatorUri' => "apps/{$keywords}"
        ]);
    }

    public function viewAction($appId)
    {
        $user = parent::checkUserLogin();

        $app = Apps::findFirstById($appId);

        if (!$app) {
            $this->flashSession->error('小程序不存在');
            $this->response->redirect();
            return;
        }

        if ($this->request->isPost()) {

            if ($content = $this->request->getPost('commentArea', 'trim')) {

                $app->number_reviews++;
                $app->modified_at = time();
                $app->save();

                $appReview                 = new AppsReviews();
                $appReview->app            = $app;
                $appReview->users_id       = $user->id;
                $appReview->content        = $content;

                $replyId = $this->request->getPost('reply-id', 'int');

                if ($replyId) {
                    $reply = AppsReviews::findFirstById($replyId);
                    $appReview->in_reply_to_id = $replyId;
                    $appReview->in_reply_to_user = $reply->users_id;
                }

                if ($appReview->save()) {

                    // if ($appReview->user->id != $user->id) {
                    //
                    //     $isFirstReply = false;
                    //     if ($post->number_replies == 1) {
                    //         $isFirstReply = true;
                    //     }
                    //     Bank::handlePostReply($user, $isFirstReply);
                    // }

                    return $this->response->redirect("app/{$app->id}#C{$appReview->id}");
                } else {
                    $this->flash->error(join('<br>', $appReview->getMessages()));
                }
            }
        }

        $ipAddress = $this->request->getClientAddress();

        $parameters = [
            'apps_id = ?0 AND ipaddress = ?1',
            'bind' => [$appId, $ipAddress]
        ];

        // A view is stored by ip address
        if (!$viewed = AppsViews::count($parameters)) {

            // Increase the number of views in the post
            $app->number_views++;

            $appView            = new AppsViews();
            $appView->app       = $app;
            $appView->ipaddress = $ipAddress;

            $appView->save();
        }

        if (AppsVotes::count(['apps_id = ?0 AND users_id = ?1', 'bind' => [$app->id, $user->id]])) {
            $this->view->setVar('votedApp', true);
        }

        $parameters = ["conditions" => "apps_id = ?0 AND users_id = ?1",
                       "bind" => [$app->id, $user->id]];

        $appVote = AppsVotes::findFirst($parameters);

        if ($appVote) {
            $this->view->setVar('votedType', $appVote->vote_type);
        } else {
            $this->view->setVar('votedType', 0);
        }

        $myApps = null;

        if ($user) {
            $parameters = [
                "creator_id = ?0",
                'bind' => [$user->id],
                "limit" => 10
            ];

            $myApps = Apps::find($parameters);
        }

        $parameters = [
            "apps_id = ?0",
            'bind' => [$app->id],
            "limit" => 30,
            "order" => "votes_up DESC"
        ];

        $reviews = AppsReviews::find($parameters);

        $title = $app->name;
        $this->tag->setTitle($title.'小程序');

        $tag1Apps = $tag2Apps = $tag3Apps = null;
        if ($app->tag1 != null) {
            $parameters = [
                "tag1_id = ?0 OR tag2_id = ?1 OR tag3_id = ?2",
                'bind' => [$app->tag1->id, $app->tag1->id, $app->tag1->id],
                "limit" => 5,
                "order" => "votes_up DESC"];

            $tag1Apps = Apps::find($parameters);
        }

        if ($app->tag2 != null) {
            $parameters = [
                "tag1_id = ?0 OR tag2_id = ?1 OR tag3_id = ?2",
                'bind' => [$app->tag2->id, $app->tag2->id, $app->tag2->id],
                "limit" => 5,
                "order" => "votes_up DESC"];

            $tag2Apps = Apps::find($parameters);
        }

        if ($app->tag3 != null) {
            $parameters = [
                "tag1_id = ?0 OR tag2_id = ?1 OR tag3_id = ?2",
                'bind' => [$app->tag3->id, $app->tag3->id, $app->tag3->id],
                "limit" => 5,
                "order" => "votes_up DESC"];

            $tag3Apps = Apps::find($parameters);
        }

        $appCount = Apps::count();

        $this->view->setVars([
            'canonical'   => "app/{$app->id}",
            'currentUser' => $user,
            'reviews'     => $reviews,
            'title'       => $title,
            'myApps'      => $myApps,
            'appTag1'     => $app->tag1,
            'appTag2'     => $app->tag2,
            'appTag3'     => $app->tag3,
            'tag1Apps'    => $tag1Apps,
            'tag2Apps'    => $tag2Apps,
            'tag3Apps'    => $tag3Apps,
            'appCount'    => $appCount,
            'theApp'      => $app
        ]);

    }

    public function createAction()
    {
        $user = parent::checkUserLogin();

        if (!$user) {
            return $this->response->redirect();
        }

        $title = '创建小程序';

        $this->tag->setTitle($title);

        $form = new AppCreateForm();

        if ($this->request->isPost()) {

            $passValidate = true;

            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
                $passValidate = false;
            }

            $appTags = $this->request->getPost('appTags');

            if ($appTags == null) {
                $this->flashSession->error('请给小程序选择一个标签');
                $passValidate = false;
            }

            if ($passValidate) {

                $name = $this->request->getPost('name', 'trim');
                $desc = $this->request->getPost('descArea');

                $app                = new Apps();
                $app->creator_id    = $user->id;
                $app->name          = $name;
                $app->desc          = $desc;

                $tagArray = explode(',', $appTags);
                if (isset($tagArray[0])) {
                    $appTag = AppsTags::getOrCreateTag($tagArray[0]);
                    $app->tag1_id = $appTag->id;
                }
                if (isset($tagArray[1])) {
                    $appTag = AppsTags::getOrCreateTag($tagArray[1]);
                    $app->tag2_id = $appTag->id;
                }
                if (isset($tagArray[2])) {
                    $appTag = AppsTags::getOrCreateTag($tagArray[2]);
                    $app->tag3_id = $appTag->id;
                }

                if ($app->save()) {
                    $this->response->redirect("publish/step2/{$app->id}");
                    return;
                } else {
                    $this->flash->error(join('<br>', $app->getMessages()));
                }
            }
        }

        $parameters = ["conditions" => "creator_id = ?0",
                       "bind" => [$user->id],
                       "limit" => 10,
                       "order" => "created_at DESC"
                      ];

        $myApps = Apps::find($parameters);

        $parameters = ["limit" => 20, "columns" => "id, name, slug, number_apps", "order" => "number_apps DESC"];
        $tagList = AppsTags::find($parameters);

        $this->view->setVars([
            'title'       => $title,
            'tagList'     => $tagList,
            'myApps'      => $myApps
        ]);

        $this->view->form = $form;
    }

    public function editAction($appId)
    {
        $user = parent::checkUserLogin();

        if (!$user) {
            return $this->response->redirect();
        }

        $app = Apps::findFirstById($appId);

        if (!$app) {
            $this->flashSession->error('小程序不存在');
            $this->response->redirect();
            return;
        }

        $parameters = ["conditions" => "creator_id = ?0 AND id = ?1",
                       "bind" => [$user->id, $app->id]
                      ];

        $isMyApp = Apps::count($parameters) > 0;

        if (!$isMyApp) {
            $this->flashSession->error('没有权限修改');
            $this->response->redirect();
            return;
        }

        $form = new AppEditForm();

        if ($this->request->isPost()) {

            $passValidate = true;

            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
                $passValidate = false;
            }

            $appTags = $this->request->getPost('appTags');

            if ($appTags == null) {
                $this->flashSession->error('请给小程序选择一个标签');
                $passValidate = false;
            }

            if ($app->icon_version == 0) {
                $this->flash->error('请上传小程序图标');
                $passValidate = false;
            } else if ($app->qrcode_version == 0) {
                $this->flash->error('请上传小程序二维码');
                $passValidate = false;
            } else if ($app->screen1_version == 0 && $app->screen2_version == 0 && $app->screen3_version == 0) {
                $this->flash->error('请上传最少一张小程序截图');
                $passValidate = false;
            }

            if ($passValidate) {

                $name = $this->request->getPost('name', 'trim');
                $desc = $this->request->getPost('descArea');

                $app->name          = $name;
                $app->desc          = $desc;
                $app->status        = Apps::APP_STATUS_PUBLIC;

                $tagArray = explode(',', $appTags);
                if (isset($tagArray[0])) {
                    $appTag = AppsTags::getOrCreateTag($tagArray[0]);
                    $app->tag1_id = $appTag->id;
                }
                if (isset($tagArray[1])) {
                    $appTag = AppsTags::getOrCreateTag($tagArray[1]);
                    $app->tag2_id = $appTag->id;
                }
                if (isset($tagArray[2])) {
                    $appTag = AppsTags::getOrCreateTag($tagArray[2]);
                    $app->tag3_id = $appTag->id;
                }


                if ($app->save()) {
                    $this->response->redirect("app/{$app->id}");
                    return;
                } else {
                    $this->flash->error(join('<br>', $app->getMessages()));
                }
            }
        } else {

            $tagNameArray = [];

            if ($app->tag1_id) {
                $tagNameArray[] = $app->tag1->name;
            }
            if ($app->tag2_id) {
                $tagNameArray[] = $app->tag2->name;
            }
            if ($app->tag3_id) {
                $tagNameArray[] = $app->tag3->name;
            }
            $tagNames = implode(",", $tagNameArray);

            $this->tag->displayTo('id', $app->id);
            $this->tag->displayTo('appTags', $tagNames);
            $this->tag->displayTo('name', $app->name);
            $this->tag->displayTo('descArea', $app->desc);
        }

        $parameters = ["conditions" => "creator_id = ?0",
                       "bind" => [$user->id],
                       "limit" => 10,
                       "order" => "created_at DESC"
                      ];

        $myApps = Apps::find($parameters);

        $title = '修改小程序';

        $this->tag->setTitle($title);

        $parameters = ["limit" => 20, "columns" => "id, name, slug, number_apps", "order" => "number_apps DESC"];
        $tagList = AppsTags::find($parameters);

        $this->view->setVars([
            'title'       => $title,
            'tagList'     => $tagList,
            'myApps'      => $myApps,
            'theApp'      => $app
        ]);

        $this->view->form = $form;
    }

    public function uploadIconsAction($appId)
    {
        $user = parent::checkUserLogin();

        if (!$user) {
            return $this->response->redirect();
        }

        $app = Apps::findFirstById($appId);

        if (!$app) {
            $this->flashSession->error('小程序不存在');
            $this->response->redirect();
            return;
        }

        if ($this->request->isPost()) {

            $passValidate = true;

            if ($app->icon_version == 0) {
                $this->flash->error('请上传小程序图标');
                $passValidate = false;
            } else if ($app->qrcode_version == 0) {
                $this->flash->error('请上传小程序二维码');
                $passValidate = false;
            } else if ($app->screen1_version == 0 && $app->screen2_version == 0 && $app->screen3_version == 0) {
                $this->flash->error('请最少上传一张小程序截图');
                $passValidate = false;
            }

            if ($passValidate) {

                $app->status = Apps::APP_STATUS_PUBLIC;

                if ($app->save()) {
                    $this->response->redirect("app/{$app->id}");
                    return;
                } else {
                    $this->flash->error(join('<br>', $app->getMessages()));
                }
            }
        }

        $parameters = ["conditions" => "creator_id = ?0",
                       "bind" => [$user->id],
                       "limit" => 10,
                       "order" => "created_at DESC"
                      ];

        $myApps = Apps::find($parameters);

        $title = '上传图标、二维码、截图';
        $this->tag->setTitle($title);

        $this->view->setVar('title',  $title);
        $this->view->setVar('theApp', $app);
        $this->view->setVar('myApps', $myApps);
    }

    public function readyAction($appId)
    {
        $user = parent::checkUserLogin();

        if (!$user) {
            return $this->response->redirect();
        }

        $app = Apps::findFirstById($appId);

        if (!$app) {
            $this->flashSession->error('小程序不存在');
            $this->response->redirect();
            return;
        }

        if ($this->request->isPost()) {

            $app->status = Apps::APP_STATUS_PUBLIC;

            if ($app->save()) {
                $this->response->redirect("app/{$app->id}");
                return;
            } else {
                $this->flash->error(join('<br>', $app->getMessages()));
            }
        }

        $myApps = Apps::find($parameters);

        $title = '确认小程序信息';
        $this->tag->setTitle($title);

        $this->view->setVars([
            'canonical'   => "app/{$app->id}",
            'title'       => $title,
            'myApps'      => $myApps,
            'theApp'      => $app
        ]);
    }

    /**
     * Votes a app
     *
     * @param int $id The post ID.
     * @return ResponseInterface
     */
    public function voteAction($id = 0, $voteType = 1)
    {
        $user = parent::checkUserLogin();

        if (!$user) {
            return $this->response->redirect();
        }

        $app = Apps::findFirstById($id);
        if (!$app) {
            $contentNotExist = [
                'status'  => 'error',
                'message' => 'App does not exist'
            ];
            return $this->response->setJsonContent($contentNotExist);
        }

        $parameters = ["conditions" => "apps_id = ?0 AND users_id = ?1",
                       "bind" => [$app->id, $user->id]];

        $appVote = AppsVotes::findFirst($parameters);

        if ($appVote) {
            if($appVote->vote_type == $voteType) {
                $contentOk = [
                    'status' => 'OK'
                ];
                return $this->response->setJsonContent($contentOk);
            } else {

                $appVote->vote_type = $voteType;

                if ($voteType == AppsVotes::VOTE_DOWN) {

                    if ($app->votes_up >= 1) {
                        $app->votes_up--;
                    }
                    $app->votes_down++;

                    if ($app->user->votes_receive >= 1) {
                        $app->user->votes_receive--;
                    }
                    if ($user->votes_send >= 1) {
                        $user->votes_send--;
                        $user->save();
                    }

                    // // Delete the notification
                    // $parameters = ["conditions" => "users_id = ?0 AND posts_id = ?1 AND users_origin_id = ?2 AND type='VP'",
                    //                "bind" => [$post->users_id, $post->id, $user->id]];
                    // $notification = ActivityNotifications::findFirst($parameters);
                    //
                    // if ($notification) {
                    //     $notification->delete();
                    // }
                } else if($voteType == AppsVotes::VOTE_UP) {

                    if ($app->votes_down >= 1) {
                        $app->votes_down--;
                    }
                }
            }
        } else {
            $appVote            = new AppsVotes();
            $appVote->apps_id   = $app->id;
            $appVote->users_id  = $user->id;
            $appVote->vote_type = $voteType;
        }

        if ($appVote->save()) {

            if ($voteType == AppsVotes::VOTE_UP) {

                if ($app->users_id != $user->id) {

                    $app->votes_up++;

                    $app->user->votes_receive++;
                    $user->votes_send++;
                    $user->save();

                    // $activity                       = new ActivityNotifications();
                    // $activity->users_id             = $post->users_id;
                    // $activity->posts_id             = $post->id;
                    // $activity->users_origin_id      = $user->id;
                    // $activity->type                 = ActivityNotifications::VOTE_UP_POST;
                    // $activity->save();
                } else {
                    $app->votes_up++;
                }
            }
        } else {
            $contentError = [
                'status'  => 'error',
                'message' => (string) $appVote->getMessages()
            ];
            return $this->response->setJsonContent($contentError);
        }

        if (!$app->save()) {

            $contentError = [
                'status'  => 'error',
                'message' => (string) $app->getMessages()
            ];
            return $this->response->setJsonContent($contentError);
        }

        $contentOk = [
            'status' => 'OK'
        ];

        return $this->response->setJsonContent($contentOk);
    }

}
