<?php
// use Phalcon\Di;
// use Phalcon\Logger\Adapter\Stream;
// use GuzzleHttp\Client;
// use Sunra\PhpSimple\HtmlDomParser;

use Phosphorum\Models\Cities;

require 'cli-bootstrap.php';

function likeCity()
{
    $cityName = '北京';

    $parameters = ["conditions" => "alias LIKE '%".$cityName."%'"];

    $city = Cities::findFirst($parameters);

    echo $city->name;

}

likeCity();


// $dateStr = date("Y-m-d H:i:s", 1483535031);
// $date = new DateTime($dateStr);
// $now = new DateTime('now');
//
// $interval = $now->diff($date);
//
// echo $interval->d;

// $name = 't汉字abc';
//
// if(preg_match("/[^a-zA-Z0-9\x{4e00}-\x{9fff}]/ui", $name)) {
//     echo 'xxx';
// }

// $a = 1;
//
// echo bjsb($a);

// $ab = false;
//
// $abc = null;
//
// if ($ab) {
//     $abc = 123;
// }
//
// echo $abc;

// echo date('Y-m-d');

// echo max(222, 1, null);

// $starIcon = '<span class="glyphicon glyphicon-star star-level-%d"></span>';
//
// echo sprintf($starIcon, 1);

// $test = '0哈哈123133';
//
// if (preg_match("/[^a-zA-Z0-9_-]/i", $test)) {
//     echo "A match was found.";
// } else {
//     echo 'xxx';
// }
//
// if (is_numeric($test)) {
//     echo 'yes';
// } else {
//     echo 'no';
// }

// $new_line = '<a href="./demo/api-canvas.zip?t=20161107">下载</a>';
//
// $pattern = '/href\\s*=\\s*(?:[\"]([^\"]*)).html/';
// if (preg_match($pattern, $new_line)) {
//     $replacement = 'href="#${1}';
//     $new_line = preg_replace($pattern, $replacement, $new_line);
// }
//
// echo $new_line;

// $file = 'search-lunr.js?t=20161107';
//
// $newFile = $file;
//
// if (strstr($file, '.js') !== false) {
//     echo $file.PHP_EOL;
//     $newFile = substr($file, 0, stripos($file, '?'));
//     echo $newFile.PHP_EOL;
// }

// $str = '<p>登录<a href="https://mp.weixin.qq.com/?t=20161107" target="_blank">https://mp.weixin.qq.com</a>获取模板，如果没有合适的模板，可以申请添加新模板，审核通过后可使用，详见<a href="#api/notice.html%3Ft=20161107#审核说明">模板审核说明</a></p><p><img src="../image/mp-notice.png%3Ft=20161107"></p><ol><li><p>页面的 <a href="#component/form.html%3Ft=20161107"><code>&lt;form/&gt;</code></a> 组件，属性<code>report-submit</code>为<code>true</code>时，可以声明为需发模板消息，此时点击按钮提交表单可以获取<code>formId</code>，用于发送模板消息。或者当用户完成<a href="#api/api-pay.html%3Ft=20161107">支付行为</a>，可以获取<code>prepay_id</code>用于发送模板消息。</p></li>
// ';
//
// $new_line = str_ireplace('src="../image', 'src="./image', $str);
//
// echo $new_line;

// preg_match('/([\w]+)\/([^.]*.html)/', 'framework/view/wxml/data.html?t=20161102', $matches);
//
// print_r($matches);

// $new_line = ' <p>本文档将带你一步步创建完成一个微信小程序，并可以在手机上体验该小程序的实际效果。这个小程序的首页将会显示欢迎语以及当前用户的微信头像，点击头像，可以在新开的页面中查看当前小程序的启动日志。<a href="demo/quickstart.zip%3Ft=20161102">下载源码</a></p><h3 id="index.html?t=20161102#1-获取微信小程序的-appid">1. 获取微信小程序的 AppID</h3><p>登录 <a href="https://mp.weixin.qq.com/?t=20161102" target="_blank">https://mp.weixin.qq.com</a> ，就可以在网站的“设置”-“开发者设置”中，查看到微信小程序的 AppID 了，注意不可直接使用服务号或订阅号的 AppID 。</p><p><img src="image/setting.png%3Ft=20161102"></p><p><strong>注意：如果要以非管理员微信号在手机上体验该小程序，那么我们还需要操作“绑定开发者”。即在“用户身份”-“开发者”模块，绑定上需要体验该小程序的微信号。本教程默认注册帐号、体验都是使用管理员微信号。</strong></p><h3 id="index.html?t=20161102#2-创建项目">2. 创建项目</h3><p>我们需要通过<a href="devtools/devtools.html%3Ft=20161102.html">开发者工具</a>，来完成小程序创建和代码编辑。</p><p>开发者工具安装完成后，打开并使用微信扫码登录。选择创建“项目”，填入上文获取到的 AppID ，设置一个本地项目的名称（非小程序名称），比如“我的第一个项目”，并选择一个本地的文件夹作为代码存储的目录，点击“新建项目”就可以了。</p><p>为方便初学者了解微信小程序的基本代码结构，在创建过程中，如果选择的本地文件夹是个空文件夹，开发者工具会提示，是否需要创建一个 quick start 项目。选择“是”，开发者工具会帮助我们在开发目录里生成一个简单的 demo。</p><p><img src="image/new_project.png%3Ft=20161102"></p><p>项目创建成功后，我们就可以点击该项目，进入并看到完整的开发者工具界面，点击左侧导航，在“编辑”里可以查看和编辑我们的代码，在“调试”里可以测试代码并模拟小程序在微信客户端效果，在“项目”里可以发送到手机里预览实际效果。</p><h3 id="index.html?t=20161102#3-编写代码">3. 编写代码</h3><h4 id="创建小程序实例">创建小程序实例</h4><p>点击开发者工具左侧导航的“编辑”，我们可以看到这个项目，已经初始化并包含了一些简单的代码文件。最关键也是必不可少的，是 app.js、app.json、app.wxss 这三个。其中，<code>.js</code>后缀的是脚本文件，<code>.json</code>后缀的文件是配置文件，<code>.wxss</code>后缀的是样式表文件。微信小程序会读取这些文件，并生成<a href="framework/app-service/app.html%3Ft=20161102.html">小程序实例</a>。</p><p>下面我们简单了解这三个文件的功能，方便修改以及从头开发自己的微信小程序。</p><p>app.js是小程序的脚本代码。我们可以在这个文件中监听并处理小程序的生命周期函数、声明全局变量。调用框架提供的丰富的 API，如本例的同步存储及同步读取本地数据。想了解更多可用 API，可参考 <a href="api/index.html%3Ft=20161102.html">API 文档</a></p><pre><code class="lang-javascript"><span class="hljs-comment">//app.js</span>';
//
// //echo $new_line;
//
// $pattern = '/href\\s*=\\s*(?:[\"]([^\"]*)).html/';
// if (preg_match($pattern, $new_line)) {
//     $replacement = 'href="#${1}';
//     $new_line2 = preg_replace($pattern, $replacement, $new_line);
//     echo $new_line2;
// }
//
// echo '-----------'.PHP_EOL;

//echo $new_line2;

// $aid = sprintf("%06s", '8881001');
//
// echo $aid;

// $level = (int) (9999 / 1000);
//
// if ($level > 10) {
//     $level = 10;
// }
//
// echo $level;

// $date  = '9-2';
// $date2 = '09-02';
//
// $parts1 = explode('-', $date);
// $parts2 = explode('-', $date2);
//
// if ($parts1[0] == $parts2[0] && $parts1[1] == $parts2[1]) {
//     echo 'yes';
// }


// $parts = explode('@', 'tes.=-t-ts00999t@test.com');
//
// print_r($parts);
//
// echo preg_replace('/[^\d\w-_]/i', '', $parts[0]);
//
// echo PHP_EOL;

// $a = false;
// $b = '111';
//
// $c = $a ?: $b;
//
// echo $c;

// function getConfig() {
//     return
// }
// $test = require_once BASE_DIR . 'app/config/timezones.php';
//
// print_r($test);

//
// $str = '世预赛亚洲区B组第9轮';
//
// echo mb_substr($str, 0, -3);

// preg_match('/[\d]+/', '第10轮', $matches);
//
// print_r($matches);

// $tomorrowTime = mktime(0, 0, 0, date('m'), date('d'), date('Y')+1);
//
// $tomorrow = date("Y-m-d", $tomorrowTime);
//
// echo $tomorrow;

//
// $pid = 'xxxx';
//
// $url = sprintf('http://sports.le.com/match/%s.html', $pid);
//
// echo $url;

// $client = new GuzzleHttp\Client(['base_uri' => 'http://sports.le.com/']);
//
// $response = $client->get('match/1024282003.html', [
//     'allow_redirects' => false
// ]);
//
// echo $response->getStatusCode();
//
// foreach ($response->getHeaders() as $name => $values) {
//     echo $name . ': ' . implode(', ', $values) . "\r\n";
// }
//
// echo $response->getBody();
//
// $dom = HtmlDomParser::file_get_html('http://sports.le.com/match/121828003.html');
//
// $text = $dom->innertext;
//
// //file_put_contents(BASE_DIR.'scripts/text.txt', $text);
//
// $list = [];
//
// $listStr = $dom->find('ol.playback-list li', 0);
//
// preg_match('/data-id="([\d]+)"/', $listStr, $matches);
//
// print_r($matches);

//print_r($response);

// date_default_timezone_set('Asia/Shanghai');

// $accpetedStr = '123123,3333,,,,,,4444';
//
// $accpetedStr = preg_replace('/[,]{2,}/', ',', $accpetedStr);
//
// echo $accpetedStr;

// $a = 102;
// $b = 101;
//
// echo $a > $b ? $a : $b;
// echo $a > $b ?: $b;


// $linkMap = ['SID' => ['乐视体育', 'http://match.sports.sina.com.cn/livecast/n/live.php?id={query}'],
//             'LID' => ['新浪体育', 'http://sports.letv.com/match/{query}.html']];
//
// function make_link_json($idList) {
//
//     global $linkMap;
//
//     $linkNew = [];
//
//     foreach ($idList as $id) {
//         $idType = substr($id, 0, 3);
//         $idVal  = substr($id, 3);
//
//         if (array_key_exists($idType, $linkMap)) {
//             $key = $linkMap[$idType][0];
//             $val = str_ireplace("{query}", $idVal, $linkMap[$idType][1]);
//
//             $linkNew[] = [$key => $val];
//         }
//     }
//     return json_encode($linkNew);
// }
//
// echo make_link_json(['SID141947', ba'LID121672003']);

// $iterator = new RecursiveIteratorIterator(
//     new RecursiveDirectoryIterator('/home/vagrant/Code/jrzhibo/app/cache/'), RecursiveIteratorIterator::CHILD_FIRST
// );
//
// $excludeDirsNames = array();
// $excludeFileNames = array('.gitignore');
//
// foreach($iterator as $entry) {
//     if ($entry->isDir()) {
//         if (!in_array($entry->getBasename(), $excludeDirsNames)) {
//             try {
//                 echo 'DIR:'.$entry->getPathname().PHP_EOL;
//             }
//             catch (Exception $ex) {
//                 // dir not empty
//             }
//         }
//     } elseif (!in_array($entry->getFileName(), $excludeFileNames)) {
//         //unlink($entry->getPathname());
//
//         echo 'File:'.$entry->getPathname().PHP_EOL;;
//     }
// }

// $str = '辽宁宏运 - 延边富德';
//
// $matchName = preg_replace('/\s-\s/', 'vs', $str);
//
// echo $matchName;

// $newValue = "1 end_time 2016-09-11 15:30";
//
// if (preg_match("/[\d]+-[\d]+-[\d]+/", $newValue)) {
//     echo 'yes';
// } else {
//     echo 'no';
// }
// $one_item = "1 name 美国 vs 捷克222";
//
// $pieces = preg_split("/[\s\t]+/", $one_item, 3);
//
// print_r($pieces);

// $now2 = new \DateTime('now');
// $now2->add(new \DateInterval("P1D"));
// $tomorrow = $now2->format('Y-m-d');
//
// echo $tomorrow;

// $now = new \DateTime('now');
// echo $now->format('Y-m-d H:i:s');
// $beginAt = new DateTime('2016-9-22 9:15:00');
//
// $diff = $now->diff($beginAt);
//
// print_r($diff);
//
// echo $diff->days.'天';
//
// if($diff->invert == 1) {
//     //已结束
// }


// $stack = array("orange", "banana", "apple", "raspberry");
//
// print_r($stack);
//
// $fruit = array_shift($stack);
// print_r($stack);
//
// array_push($stack, $fruit);
// print_r($stack);

// $list = DateTimeZone::listAbbreviations();
//
// $list = timezone_identifiers_list();
//
// print_r($list);

// Test timezone

// $beginAt = new DateTime('2016-09-25 23:00:00');
//
// $weekArray = ["日","一","二","三","四","五","六"];
// $week = "周" . $weekArray[date("w", $beginAt->getTimestamp())];
//
// echo $week;

// Test preg_split

// $keywords = preg_split("/[0-9-]+/", "悉尼FC2-2山东鲁能");
//
// preg_match("/[0-9-]+/", "悉尼FC2-2山东鲁能", $matches);
//
// print_r($matches);
// print_r($keywords);

// Test number collator_compare

// $str = '12-13';
// if(substr($str, 0, 2) >= 8) {
//     echo '>>>>>';
// } else {
//     echo '<<<<<';
// }

// find month/date
// $one_item = '12月21日 星期三';
// $two_item = '8:00 鹈鹕 @ 76人 - 富国银行中心球馆';
//
// if (preg_match("/([\d]+)月([\d]+)日/i", $two_item, $matches)) {
//     echo "A match was found.";
// } else {
//     echo 'Not found';
// }
//
// print_r($matches);

// $link_html = '';
// $live_links = '{"腾讯体育":"http://kbs.sports.qq.com/kbsweb/game.htm?mid=100002:3842", "CCTV5+":"http://tv.cctv.com/live/cctv5plus/"}';
//
// $linkObj = json_decode($live_links);
//
// if($linkObj != null) {
//     foreach ($linkObj as $name => $url) {
//         $link_html .= '<a href="'.$url.'" target="_blank">'.$name.'</a>&nbsp;&nbsp;';
//     }
// }
//
// echo $link_html;

// $total_mintues = 200;
//
// //echo $total_mintues;
// // 在120分钟之内，正在直播
// if($total_mintues <= 180) {
//     $status = '直播中';
// } else {
//     $status = '已结束';
// }
//
// echo $status;

// $now = new \DateTime('now');
// $beforeBegin = $now->sub(new DateInterval('PT2H'));
//
// echo $now->format("Y-m-d H:i:s")."\n";
// echo $beforeBegin->format("Y-m-d H:i:s")."\n";

// Read directory

// function getFileList() {
//
//     $file_list = [];
//
//     $folder = '/home/vagrant/Code/pforum/data/';
//
//     if ($handle = opendir($folder)) {
//
//         /* This is the correct way to loop over the directory. */
//         while (false !== ($entry = readdir($handle))) {
//             if($entry != '.' && $entry != '..' && !is_dir($folder.$entry)) {
//                 $file_list[] = $entry;
//             }
//         }
//
//         closedir($handle);
//     }
//
//     return $file_list;
// }
//
//
// $list = getFileList();

// print_r($list);
//
// $filename = 'General-2016-09-20.txt';
//
// preg_match("/[\d]+-[\d]+-[\d]+/", $filename, $matches);
//
// print_r($matches);
