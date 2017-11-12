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

use Phalcon\Mvc\Controller;
use Phosphorum\Models\Users;
use Phosphorum\Models\Posts;
use Phosphorum\Models\PostsReplies;
use Phalcon\Http\Response;

/**
 * Class UtilsController
 *
 * @package Phosphorum\Controllers
 * @property \Ciconia\Ciconia markdown
 */
class UtilsController extends Controller
{

    public function initialize()
    {
        $this->view->disable();
    }

    /**
     * @return Response
     */
    public function previewAction()
    {
        $response = new Response();
        if ($this->request->isPost()) {
            if ($this->session->get('identity')) {
                $content = $this->request->getPost('content');
                $response->setContent($this->markdown->render($this->escaper->escapeHtml($content)));
            }
        }
        return $response;
    }

    public function get_title($url) {

        $str = file_get_contents($url);

        if(strlen($str)>0){
            $str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
            preg_match("/\<title\>(.*)\<\/title\>/i",$str,$title); // ignore case
            return $title[1];
        }

        return null;
    }

    public function startsWith($haystack, $needle) {
         $length = strlen($needle);
         return (substr($haystack, 0, $length) === $needle);
    }

    public function fetchLinkAction()
    {
        $link = $this->request->get('linkURL');

        $is_http = $this->startsWith($link, 'http://');
        $is_https = $this->startsWith($link, 'https://');

        $title = null;

        if ($is_http || $is_https) {
            $title = $this->get_title($link);
        } else {
            $link = 'http://' . $link;
            $title = $this->get_title($link);
        }

        $contentOk = [
            'status'  => 'ok',
            'title' => $title
        ];

        return $this->response->setJsonContent($contentOk);
    }
}
