<?php
define('ORIGIN','https://pet-chain.baidu.com');
define('JSON','application/json');
define('APP_ID','');
define('API_KEY','');
define('SECRET_KEY','');


$time=time().'451';
$cookie="";

$data= ["pageNo"=>1,"pageSize"=>10,"querySortType"=>"AMOUNT_ASC","petIds"=>[],"lastAmount"=>null,"lastRareDegree"=>null,"requestId"=>$time,"appId"=>1,"tpl"=>""];
$listPetUrl='https://pet-chain.baidu.com/data/market/queryPetsOnSale';
$cmd=buildCmd($listPetUrl,ORIGIN,JSON,ORIGIN,$cookie,$data);

exec($cmd,$output,$return_arr);
if(!isset($output[0])){
	continue;
}
$info=json_decode($output[0],true);
$output=[];
foreach($info['data']['petsOnSale'] as $item){
	if(intval($item['amount'])>1000){
		continue;
	}

	$petUrl= "https://pet-chain.baidu.com/chain/detail?channel=market&petId=".$item['petId']."&validCode=".$item['validCode'];
	echo $petUrl.PHP_EOL;

	$getCaptcha='https://pet-chain.baidu.com/data/captcha/gen';
	$cmd=buildCmd($getCaptcha,ORIGIN,JSON,$petUrl,$cookie,['requestId'=>$time,'appId'=>1,'tpl'=>'']);
	@exec($cmd,$output,$return_arr);
	// var_dump($output);
	$captInfo=@json_decode($output[0],true);
	if(!$captInfo){
		continue;
	}
	$captcha=idlOcr(base64_decode($captInfo['data']['img']));
	if(!$captcha){
		return '';
	}
	echo $captcha;
	$submitUrl='https://pet-chain.baidu.com/data/txn/create';
	$submitData=['petId'=>$item['petId'],'amount'=>$item['amount'],'seed'=>$captInfo['data']['seed'],'captcha'=>$captcha,'validCode'=>$item['validCode'],'requestId'=>$time,'appId'=>1,'tpl'=>''];
	$cmd=buildCmd($submitUrl,ORIGIN,JSON,$petUrl,$cookie,$submitData);
	@exec($cmd,$output,$return_arr);
	var_dump($output);
}

function buildCmd($url,$origin,$contenttype,$referer,$cookie,$data){
	$time=time().'451';
	$dataStr=json_encode($data);
	$cmd="curl '{$url}' -H 'Origin: {$origin}' -H 'Accept-Encoding: gzip, deflate, br' -H 'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7,zh-TW;q=0.6' -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36' -H 'Content-Type: {$contenttype}'  -H 'Accept: application/json' -H 'Referer: {$referer}' -H 'Cookie: {$cookie}' -H 'Connection: keep-alive' --data-binary '{$dataStr}' --compressed";
	return $cmd;
}


function idlOcr($pic_content)
{
	file_put_contents('ab.png',$pic_content);
	echo $pic_content;
	require_once 'AipOcr.php';
	$client = new AipOcr(APP_ID, API_KEY, SECRET_KEY);
	$info=$client->basicGeneral($pic_content);
	//高精
	// $info=$client->basicAccurate($pic_content);
	if($info){
		var_dump($info);
		var_dump($info['words_result']);
		return trim(@$info['words_result'][0]['words']);
	}
	return '';
}


