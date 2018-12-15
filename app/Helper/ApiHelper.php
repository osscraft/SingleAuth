<?php

namespace App\Helper;

use App\Helper\Traits\Curl;

class ApiHelper
{
    use Curl;

    /**
     * GET请求内部接口
     */
    public function httpGet($api, $headers = [])
    {
        // $requestId = app('request')->requestId;
        // $time = time()
        // $encrypt = encrypt("$requestId, ");
        // $headers[] = "SA-Inner-Authorization: $encrypt";
        return $this->curlGet(url($api), $headers);
    }

    /**
     * POST请求内部接口
     */
    public function httpPost($api, $data = [], $headers = [])
    {
        return $this->curlPost(url($api), $data, $headers);
    }

    /**
     * 解析结果
     */
    public function convert($data)
    {
        $json = json_decode($data);
        if(empty($json)) {
            throw new \Exception("9999|接口调用失败($data)");
        } else if(isset($json->rsp) && $json->rsp == 0) {
            $error = $json->error;
            $msg = $json->msg;
            throw new \Exception("$error|$msg");
        }
        return isset($json->data) ? $json->data : $json;
    }
}