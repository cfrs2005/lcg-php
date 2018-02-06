<?php
set_time_limit(0);
define('APP_PATH', __DIR__);
define('ORIGIN', 'https://pet-chain.baidu.com');
define('JSON', 'application/json');
define('APP_ID', '');
define('API_KEY', '');
define('SECRET_KEY', '');

define('SHOW_API_ID', '');
define('SHOW_API_SECRET', '');

define('ORC_FUNC', 'showApiOcr');
define('MAX_AMOUNT', 2000);
require_once __DIR__ . '/lib/AipOcr.php';
require_once __DIR__ . '/lib/ShowOcr.php';


help();
$cookie = "";
$time = time() . '451';
$data = ["pageNo" => 1, "pageSize" => 10, "querySortType" => "AMOUNT_ASC", "petIds" => [], "lastAmount" => null, "lastRareDegree" => null, "requestId" => $time, "appId" => 1, "tpl" => ""];
$listPetUrl = 'https://pet-chain.baidu.com/data/market/queryPetsOnSale';
$cmd = buildCmd($listPetUrl, ORIGIN, JSON, ORIGIN, $cookie, $data);

exec($cmd, $output, $return_arr);
if (!isset($output[0])) {
    _log("empty respone");
    exit();
}
$info = json_decode($output[0], true);
$output = [];
foreach ($info['data']['petsOnSale'] as $item) {
    _log($item['petId'] . "\t价格:\t" . $item['amount']);
    if (intval($item['amount']) > MAX_AMOUNT) {
        continue;
    }
    _log("执行买入\t" . $item['petId']);
    $petUrl = "https://pet-chain.baidu.com/chain/detail?channel=market&petId=" . $item['petId'] . "&validCode=" . $item['validCode'];
    $getCaptcha = 'https://pet-chain.baidu.com/data/captcha/gen';
    $cmd = buildCmd($getCaptcha, ORIGIN, JSON, $petUrl, $cookie, ['requestId' => $time, 'appId' => 1, 'tpl' => '']);
    @exec($cmd, $output, $return_arr);
    $captInfo = @json_decode($output[0], true);
    if (!$captInfo) {
        continue;
    }
    $captcha = call_user_func(ORC_FUNC, base64_decode($captInfo['data']['img']));
    if (!$captcha) {
        _log("empty captcha,识别失败");
        continue;
    }
    _log("captcha is :\t" . $captcha);
    $submitUrl = 'https://pet-chain.baidu.com/data/txn/create';
    $submitData = ['petId' => $item['petId'], 'amount' => $item['amount'], 'seed' => $captInfo['data']['seed'], 'captcha' => $captcha, 'validCode' => $item['validCode'], 'requestId' => $time, 'appId' => 1, 'tpl' => ''];
    $cmd = buildCmd($submitUrl, ORIGIN, JSON, $petUrl, $cookie, $submitData);
    @exec($cmd, $output, $return_arr);
    $result = @json_decode($output[1], true);
    if ($result) {
        if (@$result['errorNo'] > 10000) {
            _log("执行买入\t" . $item['petId'] . "\t失败\t" . $result['errorMsg']);
        } else {
            _log("执行买入\t" . $item['petId'] . "\t成功" . $result['errorMsg']);
        }
    } else {
        _log('service 500');
    }
}


function help()
{
    _log('黑产 百度 pet-chain');
    _log("当前价格最大限制为:\t" . MAX_AMOUNT);
}

/**
 * @param $url
 * @param $origin
 * @param $contenttype
 * @param $referer
 * @param $cookie
 * @param $data
 * @return string
 */
function buildCmd($url, $origin, $contenttype, $referer, $cookie, $data)
{
    $dataStr = json_encode($data);
    $cmd = "curl '{$url}' -H 'Origin: {$origin}' -H 'Accept-Encoding: gzip, deflate, br' -H 'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7,zh-TW;q=0.6' -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36' -H 'Content-Type: {$contenttype}'  -H 'Accept: application/json' -H 'Referer: {$referer}' -H 'Cookie: {$cookie}' -H 'Connection: keep-alive' --data-binary '{$dataStr}' --compressed";
    return $cmd;
}

/**
 * 百度
 * @param $pic_content
 * @return string
 */
function idlOcr($pic_content)
{
    file_put_contents('ab.png', $pic_content);
    $client = new AipOcr(APP_ID, API_KEY, SECRET_KEY);
    $info = $client->basicGeneral($pic_content);
    //高精
    // $info=$client->basicAccurate($pic_content);
    if ($info) {
        _log(json_encode($info));
        return trim(@$info['words_result'][0]['words']);
    }
    return '';
}

/**
 * show api
 * @param $pic_content
 * @return string
 */
function showApiOcr($pic_content)
{
    $showOcr = new ShowOcr(SHOW_API_ID, SHOW_API_SECRET);
    return $showOcr->getCaptcha(base64_encode($pic_content));
}

/**
 * @param $str
 */
function _log($str)
{
    echo date('Y-m-d H:i:s') . "\t" . $str . PHP_EOL;
}
