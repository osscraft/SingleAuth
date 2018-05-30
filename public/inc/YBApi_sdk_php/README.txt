一、文档目录结构

-- /
  |
  |-- classes/      开放平台SDK（php）
  |
  |-- doc/          文档目录
    |
    |-- api/        phpdoc生成的类api文档
    |
    |--
  |
  |-- examples/     测试实例
    |
    |-- demo/       DEMO测试项目
      |
      |-- config.php      配置文件，您需要修改这个文件写入对应的 AppID 等信息
      |
      |-- index.php       使用此文件浏览实例
      |
      |-- authorize.php   授权认证流程
      |
      |-- apicomm.php     功能接口测试（需要完成授权流程获取到access_token才能进行接口测试）
      |
      |-- frame.php       站内应用授权实例
  |
  |-- README.txt          本文档


====================================================================

二、SDK简要说明

  1、将 classes/ 目录下所有文件放到您的项目中，保持里面的目录结构
  2、应用程序中包含 yb-globals.inc.php 文件
  3、实例化 YBOpenApi::getInstance() 对象
  4、根据使用的接口，调用 init() 或 bind() 设置YBOpenApi的配置信息
  5、调用 getFrameUtil()、getAuthorize()、getUser()、getFriend() 方法实例化对应接口对象
  6、调用对应对象的访问完成接口访问

============================================================

三、简单示范

  变量说明：
  $appid       应用的AppID
  $appsec      应用的AppSecret
  $callback    回调地址（对应站内应用的是网站地址）
  $token       access_token访问令牌

  1、站内应用接入
    -------------------------------
  
    $info = YBOpenApi::getInstance()->init($appid, $appsec, $callback)->getFrameUtil()->perform();
  
    ---------------------------------------------------------------------------
  
    只需要调用一次，会自动重定向到授权服务器进行授权认证。
    完成授权后 $info 里存放有访问令牌等相关信息
  
  2、其它授权验证流程
    
    （1） $url = YBOpenApi::getInstance()->init($appid, $appsec, $callback)->getAuthorize()->forwardurl();
	
          -- 获取授权验证码的地址，浏览器需要重定向到这个地址进行授权请求
          
    （2） $info = YBOpenApi::getInstance()->init($appid, $appsec, $callback)->getAuthorize()->querytoken($_GET['code']);
	
          -- 从授权服务器返回后，通过授权码code值来获取access_token。
		  
    （3）其它接口调用例子，查看 examples/demo/apicomm.php 文件
  