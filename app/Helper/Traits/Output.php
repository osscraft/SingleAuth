<?php

namespace App\Helper\Traits;

use League\OAuth2\Server\Exception\OAuthServerException;

trait Output
{
    /**
     * 错误输出
     */
    public function success($data = '')
    {
        $reqid = app()->request->requestId;
        return ['rsp' => 1, 'data' => $data, 'reqid' => $reqid];
    }

    /**
     * 错误输出
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

            return ['rsp' => 0, 'error' => $error, 'msg' => $msg, 'data' => $data, 'reqid' => $reqid];
        } else {
            if(!empty($e)) {
                return $this->error(GLOBAL_ERR_1000, "{$message} in {$data}");
            }
            $trace = debug_backtrace();
            $file = $trace[0]['file'];
            $line = $trace[0]['line'];
            $data = "{$message} in {$file}:{$line}";

            return $this->error(GLOBAL_ERR_1000, $data);
        }
    }
}
