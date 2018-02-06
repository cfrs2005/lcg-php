<?php

class BaiduPetModel
{
    private $requestTimeId;

    const ORIGIN = "https://pet-chain.baidu.com";
    const PET_INFO_URL = "https://pet-chain.baidu.com/chain/detail?channel=market";
    const PET_LIST_URL = "https://pet-chain.baidu.com/data/market/queryPetsOnSale";
    const PET_CAPTCHA_URL = "https://pet-chain.baidu.com/data/captcha/gen";


    public function __construct()
    {
        $this->requestTimeId = time() . "451";

    }

    /**
     * @param int $pageNo
     * @param int $pageSize
     * @return string
     */
    public function getListUrlCmd($pageNo = 1, $pageSize = 10)
    {
        $data = [
            "pageNo" => $pageNo,
            "pageSize" => $pageSize,
            "querySortType" => "AMOUNT_ASC",
            "petIds" => [],
            "lastAmount" => null,
            "lastRareDegree" => null,
            "requestId" => $this->requestTimeId,
            "appId" => 1,
            "tpl" => "",
        ];
        return $this->__getCmd(PET_LIST_URL, ORIGIN, ORIGIN, $this->userCookie, $data);

    }

    public function getInfoUrlCmd()
    {

    }

    public function getCaptchaCmd()
    {

    }


    /**
     * @param $url
     * @param $origin
     * @param $referer
     * @param $cookie
     * @param $data
     * @param string $contenttype
     * @return string
     */
    private function __getCmd($url, $origin, $referer, $cookie, $data, $contenttype = 'application/json')
    {
        $dataStr = json_encode($data);
        $cmd = "curl '{$url}' -H 'Origin: {$origin}' -H 'Accept-Encoding: gzip, deflate, br' -H 'Accept-Language: zh-CN,zh;q=0.9,en-US;q=0.8,en;q=0.7,zh-TW;q=0.6' -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36' -H 'Content-Type: {$contenttype}'  -H 'Accept: application/json' -H 'Referer: {$referer}' -H 'Cookie: {$cookie}' -H 'Connection: keep-alive' --data-binary '{$dataStr}' --compressed";
        return $cmd;
    }

}