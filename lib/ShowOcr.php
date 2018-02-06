<?php

/**
 * Class Showapi
 * https://www.showapi.com/api/view/184/5
 */
class ShowOcr
{
    private $showapi_appid;

    private $showapi_secret;

    /**
     * Showapi constructor.
     * @param $appid
     * @param $secret
     */
    public function __construct($appid, $secret)
    {
        $this->showapi_appid = $appid;
        $this->showapi_secret = $secret;
    }

    /**
     * @param $img_content
     * @return string
     */
    public function getCaptcha($img_content)
    {
        $paramArr = array(
            'showapi_appid' => $this->showapi_appid,
            'img_base64' => $img_content,
            'typeId' => "34",
            'convert_to_jpg' => "0",
        );
        $param = $this->_createParam($paramArr, $this->showapi_secret);
        $url = 'http://route.showapi.com/184-5?';
        $result = $this->_curl($url, $param);
        $result = json_decode($result, true);
        if ($result && $result['showapi_res_code'] === 0) {
            return $result['showapi_res_body']['Result'];
        }
        return '';
    }

    /**
     * @param $url
     * @param string $param
     * @return mixed
     */
    private function _curl($url, $param = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * @param $paramArr
     * @param $showapi_secret
     * @return string
     */
    private function _createParam($paramArr, $showapi_secret)
    {
        $paraStr = "";
        $signStr = "";
        ksort($paramArr);
        foreach ($paramArr as $key => $val) {
            if ($key != '' && $val != '') {
                $signStr .= $key . $val;
                $paraStr .= $key . '=' . urlencode($val) . '&';
            }
        }
        $signStr .= $showapi_secret;
        $sign = strtolower(md5($signStr));
        $paraStr .= 'showapi_sign=' . $sign;
        return $paraStr;
    }
}