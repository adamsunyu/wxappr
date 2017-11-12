<?php

use Phalcon\Di;
use Phalcon\Tag;
use Phalcon\Logger\Adapter\Stream;
use Sunra\PhpSimple\HtmlDomParser;
use Phosphorum\Models\Apps;
use Phosphorum\Models\AppsTags;
use PHPThumb\GD;

require realpath(dirname(dirname(__FILE__))) . '/scripts/cli-bootstrap.php';

function rrmdir($src) {

    $dir = opendir($src);

    if ($dir === false) {
        echo('Erorr');
        return;
    }

    while(false !== ( $file = readdir($dir)) ) {

        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if ( is_dir($full) ) {
                rrmdir($full);
            }
            else {
                unlink($full);
            }
        }
    }

    closedir($dir);
    rmdir($src);
}

function recurse_copy($src, $dst) {

    $dir = opendir($src);

    mkdir($dst, 0777, true);

    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                $newFile = $file;
                if (strstr($file, '.js') !== false) {
                    echo $file.PHP_EOL;
                    $newFile = substr($file, 0, stripos($file, '?'));
                    echo $newFile.PHP_EOL;
                }
                copy($src . '/' . $file, $dst . '/' . $newFile);
            }
        }
    }
    closedir($dir);
}

function find_all_apps() {

    $dom = HtmlDomParser::file_get_html(BASE_DIR.'data/apps.html');

    $link_list = [];

    foreach($dom->find('.miniapp-header a') as $link) {

        $hrefSrc = $link->href;

        if(!in_array($hrefSrc, $link_list)) {
             $link_list[] = $hrefSrc;
        }
    }

    foreach ($link_list as $app_link) {
    }
}

function convert_to_png($stageImage, $imgDir, $width, $height, $name) {

    // Use PHPThumb GD genreate thumbnail
    $thumb = new GD($stageImage);

    try {
        $thumb->adaptiveResize($width, $height);
        $thumb->save($imgDir.'/'.$name);
    } catch (Exception $e) {
    }
}

function download_app_image($app, $imageURL, $imageType, $imageIndex = 0) {

    $url = $imageURL;

    $imgDir = $app->imageFolder();

    if (!is_dir($imgDir)){
        mkdir($imgDir, 0777, true);
    }

    $urlInfo = parse_url($imageURL);
    $fileInfo = pathinfo($urlInfo['path']);

    $stageImage = BASE_DIR.'public/stage/'.$fileInfo['basename'];

    file_put_contents($stageImage, file_get_contents($imageURL));

    if ($imageType == 'icon') {
        convert_to_png($stageImage, $imgDir, 512, 512, 'icon.png');
        convert_to_png($stageImage, $imgDir, 80, 80, 'icon-small.png');
    } else if($imageType == 'qrcode') {
        convert_to_png($stageImage, $imgDir, 512, 512, 'qrcode.png');
        //convert_to_png($stageImage, $imgDir, 100, 100, 'qrcode-small.png');
    } else if($imageType == 'screenshot') {
        convert_to_png($stageImage, $imgDir, 720, 1280, 'screenshot'.$imageIndex.'.png');
    }

    // Delete temporary file
    unlink($stageImage);
}

function create_app($appInfo) {

    echo $appInfo['name'];

    $appName = trim($appInfo['name']);

    $app = Apps::findFirstByName($appName);

    if ($app == null) {

        $app = new Apps();
        $app->creator_id = 1;
        $app->name = $appName;
        $app->desc = $appInfo['description'];
        $app->votes_up = 0;
        $app->votes_down = 0;
        $app->number_reviews = 0;
        $app->status = Apps::APP_STATUS_PUBLIC;

        $tagIndex = 1;
        foreach ($appInfo['tag'] as $key => $oneTag) {

            $tagName = trim($oneTag['name']);

            $appTag = AppsTags::getOrCreateTag($tagName);

            if ($tagIndex == 1) {
                $app->tag1_id = $appTag->id;
            } else if($tagIndex == 2) {
                $app->tag2_id = $appTag->id;
            } else if($tagIndex == 3) {
                $app->tag3_id = $appTag->id;
            }

            $tagIndex++;
        }

        if(!$app->save()) {
            echo(join('<br>', $app->getMessages()));
        }

        echo " New \n";
    } else {
        echo "  Found\n";
    }

    $imgDir = $app->imageFolder();

    if (!file_exists($imgDir . '/icon.png')) {
        echo "Download icon \n";
        download_app_image($app, $appInfo['icon']['image'], 'icon');
        $app->icon_version += 1;
    } else {
        echo "Skip icon \n";
    }

    if (!file_exists($imgDir . '/qrcode.png')) {
        echo "Download qrcode \n";
        download_app_image($app, $appInfo['qrcode']['image'], 'qrcode');
        $app->qrcode_version += 1;
    } else {
        echo "Skip qrcode \n";
    }

    foreach ($appInfo['screenshot'] as $key => $oneScreen) {

        $imgDir = $app->imageFolder();

        // unlink($imgDir . '/screenshot0.png');
        // unlink($imgDir . '/screenshot1.png');
        // unlink($imgDir . '/screenshot2.png');
        // unlink($imgDir . '/screenshot3.png');
        // unlink($imgDir . '/screenshot4.png');
        // unlink($imgDir . '/screenshot5.png');
        //
        // $app->screen1_version = 0;
        // $app->screen2_version = 0;
        // $app->screen3_version = 0;
        // $app->screen4_version = 0;
        // $app->screen5_version = 0;

        if (!file_exists($imgDir . '/screenshot'.($key+1).'.png')) {

            echo "Download screenshot".($key+1)." \n";
            download_app_image($app, $oneScreen['image'], 'screenshot', ($key+1));

            if ($key == 0) {
                $app->screen1_version += 1;
            }
            if ($key == 1) {
                $app->screen2_version += 1;
            }
            if ($key == 2) {
                $app->screen3_version += 1;
            }

        } else {
            echo "Skip screenshot".$key." \n";
        }
    }


    if(!$app->save()) {
        echo(join('<br>', $app->getMessages()));
    }
}

function find_base_api() {

    $base_url = 'https://minapp.com/api/v3/trochili/miniapp/';
    $total_count = json_decode(file_get_contents($base_url), true)['meta']['total_count'];
    $objects = json_decode(file_get_contents($base_url . '?limit='.$total_count), true)['objects'];

    foreach ($objects as $key => $value) {
        create_app($value);
    }
}

function crawl_apps() {

    $log = new Stream('php://stdout');
    $log->info('Start');

    find_base_api();

    $log->info('End');
}

function check_screenshots() {
    $appList = Apps::find();

    foreach ($appList as $app) {
        $imgDir = $app->imageFolder();

        if (!file_exists($imgDir . 'scrrenshot1.png')) {
            $app->screen1_version = 0;
        }
    }
}

crawl_apps();
