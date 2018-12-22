<?php

$app = require_once __DIR__.'/../bootstrap/app.php';


define('LOG_FILE', storage_path('logs/wx.input.log'));

//define your token
define("TOKEN", "weixin");

$wechatObj = new WechatCallbackApi();
$wechatObj->app = $app;
$app->router->addRoute(['GET', 'POST'], '/', function() use ($wechatObj) {
	return $wechatObj->valid();//使用-》访问类中valid方法，用来验证开发模式
	// return $wechatObj->responseMsg();
});
$app->run();
// 
class WechatCallbackApi
{
	public $app;
	public function valid()//验证接口的方法
    {
        $echoStr = $_GET["echostr"];//从微信用户端获取一个随机字符赋予变量echostr
 
        //valid signature , option访问地61行的checkSignature签名验证方法，如果签名一致，输出变量echostr，完整验证配置接口的操作
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }
    //公有的responseMsg的方法，是我们回复微信的关键。以后的章节修改代码就是修改这个。
    public function responseMsg()
    {
        echo '';
    }
    	//签名验证程序	，checkSignature被18行调用。官方加密、校验流程：将token，timestamp，nonce这三个参数进行字典序排序，然后将这三个参数字符串拼接成一个字符串惊喜shal加密，开发者获得加密后的字符串可以与signature对比，表示该请求来源于微信。
	private function checkSignature()
	{
        $signature = $_GET["signature"];//从用户端获取签名赋予变量signature
        $timestamp = $_GET["timestamp"];//从用户端获取时间戳赋予变量timestamp
        $nonce = $_GET["nonce"];	//从用户端获取随机数赋予变量nonce
        		
		$token = TOKEN;//将常量token赋予变量token
		$tmpArr = array($token, $timestamp, $nonce);//简历数组变量tmpArr
		sort($tmpArr, SORT_STRING);//新建排序
		$tmpStr = implode( $tmpArr );//字典排序
		$tmpStr = sha1( $tmpStr );//shal加密
		//tmpStr与signature值相同，返回真，否则返回假
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}
