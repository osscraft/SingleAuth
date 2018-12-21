<?php

namespace App\Helper\Traits;

use GatewayWorker\Lib\Gateway;
use League\OAuth2\Server\Exception\OAuthServerException;

trait Transmit
{
    /**
     * 发送给指定用户UID
     */
    public function sendToUid($uid, $event, $data = [])
    {
        $params = $this->success($event, $data);
        $message = json_encode($params);
        Gateway::sendToUid($uid, "{$message}\n");
    }

    /**
     * 发送给指定组
     */
    public function sendToGroup($groupId, $event, $data = [])
    {
        $params = $this->success($event, $data);
        $message = json_encode($params);
        Gateway::sendToGroup($groupId, "{$message}\n");
    }

    /**
     * 发送给指定客户端
     */
    public function sendToClient($clientId, $event, $data = [])
    {
        $params = $this->success($event, $data);
        $message = json_encode($params);
        Gateway::sendToClient($clientId, "{$message}\n");
    }

    /**
     * 发送给当前客户端
     */
    public function sendToCurrentClient($event, $data = [])
    {
        $params = $this->success($event, $data);
        $message = json_encode($params);
        Gateway::sendToCurrentClient("{$message}\n");
    }

    /**
     * 发送给所有
     */
    public function sendToAll($event, $data = [])
    {
        $params = $this->success($event, $data);
        $message = json_encode($params);
        Gateway::sendToAll("{$message}\n");
    }

    /**
     * 成功数据
     */
    public function success($event, $data = '')
    {
        $reqid = app()->request->requestId;
        return ['event' => $event, 'data' => $data, 'reqid' => $reqid];
    }

    /**
     * 错误数据
     */
    public function error($message, $data = '')
    {
        $reqid = app()->request->requestId;
        if($message instanceof OAuthServerException) {
            $e = $message;
            $hint = $e->getHint();
            $msg = empty($hint) ? $e->getMessage() : $hint;
            $message = $e->getErrorType() . "|" . $msg;
            $file = $e->getFile();
            $line = $e->getLine();
            $data = "{$msg} in {$file}:{$line}";
        } else if($message instanceof \Exception) {
            $e = $message;
            $error = $e->getCode();
            $message = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            $data = "{$file}:{$line}";
        }
        if(preg_match('/^([0-9a-zA-Z_]+)\|(.+)$/',$message,$result) > 0) {
            list(,$error, $msg) = $result;

            return ['event' => 'error', 'error' => $error, 'msg' => $msg, 'data' => $data, 'reqid' => $reqid];
        } else {
            if(!empty($e)) {
                return $this->error(GLOBAL_ERR_2000, "{$message} in {$data}");
            }
            $trace = debug_backtrace();
            $file = $trace[0]['file'];
            $line = $trace[0]['line'];
            $data = "{$message} in {$file}:{$line}";

            return $this->error(GLOBAL_ERR_2000, $data);
        }
    }
}
