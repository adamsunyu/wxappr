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
use Phalcon\Mvc\View;
use Phosphorum\Models\Images;
use Phosphorum\Utils\UploadHandler;
use Phosphorum\Models\Apps;

/**
 * Class ImageController
 *
 * @package Phosphorum\Controllers
 */
class ImageController extends ControllerBase
{
    public function initialize()
    {
        $this->view->disable();
    }

    public function uploadAction()
    {
        $response = new Response();

        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $authError = [
                'status'  => 'error',
                'msg' => '需要登录才能上传'
            ];
            return $response->setJsonContent($authError);
        }

        $uploader = new UploadHandler();

        // Specify the list of valid extensions
        $uploader->allowedExtensions = array('jpeg', 'jpg', 'png', 'gif');

        // Specify max file size in bytes = 500 * 1024
        $uploader->sizeLimit = 512000;

        // Specify the input name set in the javascript.
        $uploader->inputName = "qqfile"; // matches Fine Uploader's default inputName value by default

        // If you want to use the chunking/resume feature, specify the folder to temporarily save parts.
        $uploader->chunksFolder = BASE_DIR . "public/images/chunks";

        $method = $_SERVER["REQUEST_METHOD"];

        if ($method == "POST") {

            header("Content-Type: text/plain");

            $newImage = new Images();
            $newImage->users_id = $usersId;
            $newImage->save();

            // 生成文件名
            $extension = $uploader->getFileExtension();
            $imgName = $newImage->id.'.'.$extension;

            $result = $uploader->handleUpload(BASE_DIR . "public/images", $imgName);

            $result["uploadName"] = $imgName;

            echo json_encode($result);
        }
        // for delete file requests
        else if ($method == "DELETE") {
            $result = $uploader->handleDelete(BASE_DIR . "public/images");
            echo json_encode($result);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
    }

    public function avatarUploadAction()
    {

    }

    public function appImageUploadAction($imageType, $appId, $imgId = 1)
    {
        $response = new Response();

        $usersId = $this->session->get('identity');

        if (!$usersId) {
            $authError = [
                'error' => '需要登录才能上传'
            ];
            return $response->setJsonContent($authError);
        }

        $app = Apps::findFirstById($appId);

        if (!$app) {
            $error = [
                'error' => '小程序不存在'
            ];
            return $response->setJsonContent($error);
        }

        $uploader = new UploadHandler();

        // Specify the list of valid extensions
        $uploader->allowedExtensions = array('jpeg', 'jpg', 'png');

        // Specify max file size in bytes = 500 * 1024
        $uploader->sizeLimit = 1024000;

        // Specify the input name set in the javascript.
        // matches Fine Uploader's default inputName value by default
        $uploader->inputName = "qqfile";

        $uploader->name = $appId;

        $method = $_SERVER["REQUEST_METHOD"];

        if ($method == "POST") {

            $imageName = 'default.png';

            $resizeInfo = [512, 512];

            if ($imageType == 'icon') {
                $imageName = 'icon.png';
            } else if($imageType == 'qrcode') {
                $imageName = 'qrcode.png';
            } else if($imageType == 'screenshot') {
                $imageName = 'screenshot'.$imgId.'.png';
                $resizeInfo = [720, 1280];
            }

            $result = $uploader->handleAppImageUpload($app->imageFolder(), $imageName, $app, $resizeInfo);

            if ($result['success']) {

                if ($imageType == 'icon') {

                    $app->icon_version += 1;
                    $app->save();
                    $result['imageURI'] = $app->iconURI();

                } else if($imageType == 'qrcode') {

                    $app->qrcode_version += 1;
                    $app->save();
                    $result['imageURI'] = $app->qrcodeURI();

                } else if($imageType == 'screenshot') {

                    if ($imgId == 1) {
                        $app->screen1_version += 1;
                    } else if ($imgId == 2) {
                        $app->screen2_version += 1;
                    } else if ($imgId == 3) {
                        $app->screen3_version += 1;
                    } else if ($imgId == 4) {
                        $app->screen4_version += 1;
                    } else if ($imgId == 5) {
                        $app->screen5_version += 1;
                    }

                    $app->save();
                    $result['imageURI'] = $app->screenshotURI($imgId);
                }
            }

            header("Content-Type: text/plain");
            echo json_encode($result);
        }
        // for delete file requests
        else if ($method == "DELETE") {
            $result = $uploader->handleDelete(BASE_DIR . "public/images");
            echo json_encode($result);
        }
        else {
            header("HTTP/1.0 405 Method Not Allowed");
        }
    }

}
