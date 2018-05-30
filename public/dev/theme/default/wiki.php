<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>文档</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="main">
  <div class="header">
    <div class="block_header">
      <div class="logo"><a href="index.php"><img src="../images/logo.png" border="0" alt="logo" /></a></div>
      <div class="search">
      </div>
      <div class="menu" style="margin:25px 0 0 0;">
        <ul>
          <li><a href="index.php"><span>首页</span></a></li>
          <!--<li><a href="development.php"><span>应用开发</span></a></li>-->
          <li><a href="wiki.php" class="active"><span>文档</span></a></li>
          <li><a href="download.php"><span>下载</span></a></li>
          <li><a href="support.php"><span>支持</span></a></li>
        </ul>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <div class="slider_top">
    <div class="header_text2">
      <!--<a href="#"><img src="../images/Sing_up.gif" alt="picture" border="0" /></a>-->
      <h2>文档</h2>
      <div class="clr"></div>
    </div>
  </div>
  <div class="top_bg2">
   <div class="clr"></div>
  </div>
  <div class="clr"></div>
   <div class="body">
    <div class="body_resize">
      <div class="left">
        <p><span style="font-size:16px;">目录</span></p>
        <ul style="margin:0 0 0 25px;">
          <li>一、<a href="#explain">名词解释</a></li>
          <li>二、<a href="#API">API文档</a></li>
          <li class="li1">1.<a href="#OAuth2">OAuth2</a></li>
          <li class="li2">1.1<a href="#authorize">请求授权</a></li>
          <li class="li2">1.2<a href="#token">获取授权</a></li>
          <li class="li1">2.<a href="#account">帐号</a></li>
          <li class="li2">2.1<a href="#logout">登出接口</a></li>
          <li class="li1">3.<a href="#user">用户</a></li>
          <li class="li2">3.1<a href="#resource">读取接口</a></li>
          <li>三、<a href="#OAuth2.0">OAuth2.0</a></li>
          <li class="li1">1.<a href="#summary">概述</a></li>
          <li class="li1">2.<a href="#flow">基本流程</a></li>
          <li class="li1">3.<a href="#interface">接口文档</a></li>
          <li class="li1">4.<a href="#scene">应用场景</a></li>
          <li class="li2">4.1<a href="#scene-web">Web应用的验证授权</a></li>
          <li class="li2">4.2<a href="#scene-client">客户端应用的验证授权</a></li>
          <li class="li2">4.3<a href="#scene-js">Javascript应用的验证授权</a></li>
          <li class="li1">5.<a href="#error">错误码</a></li>
        </ul>
        <ul style="margin:0 0 0 25px;">
        </ul>
        <h2 id="explain">名词解释</h2>
        <div class="clr"></div>
        <p>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="100">名词</th><th>说明</th></tr>
                <tr><th>client</th><td>客户端，文档中的客户端与平常所说的“客户端”并不相同，是相对资源服务器和授权服务器来说的，它可能指第三方应用的服务器程序或客户端程序。</td></tr>
                <tr><th>resource owner</th><td>资源拥有者，能够对受保护资源进行访问许可控制的实体。即此文档中指的用户，有教师、学生及其他。</td></tr>
                <tr><th>authorization server</th><td>授权服务器，能够成功验证资源拥有者和获取授权，并在此之后分发令牌的服务器。</td></tr>
                <tr><th>resource server</th><td>资源服务器，能够接受和响应受保护资源请求的服务器。</td></tr>
                <tr><th>authentication request</th><td>授权请求，向授权服务器发起授权请求。</td></tr>
                <tr><th>authentication grant</th><td>授权许可，授权服务器分发的授权许可证书，授权码即是其中一种。</td></tr>
                <tr><th>access token</th><td>访问令牌，被客户端用来代表资源拥有者发送验证请求的令牌。</td></tr>
                <tr><th>protected resource</th><td>受保护资源，能够使用OAuth请求获取的访问限制性资源。</td></tr>
                <tr><th>user-agent</th><td>user-agent</td></tr>
                <tr><th>endpoint</th><td>endpoint。</td></tr>
                <tr><th>client identifier</th><td>分发给客户端的唯一标识，即文档中的客户端ID，用于客户端向授权服务器标识自己。客户端标识符可以有一个对应的密钥。</td></tr>
                <tr><th>redirection uri</th><td>回调URI。</td></tr>
                <tr><th>user authenticates</th><td>用户授权，用户输入帐号和密码并提交。</td></tr>
                <tr><th>authorization code</th><td>授权码，一个短期令牌，代表终端用户的授权。用来获取访问令牌的一种证书。</td></tr>
                <tr><th>refresh token</th><td>刷新令牌，被客户端用来获取新的访问令牌的令牌，而不用资源拥有者的参与。</td></tr>
                <tr><th>optional</th><td>可选的。</td></tr>
                <tr><th>password credentials</th><td>密码证书，包括用户名和密码。用来获取访问令牌的一种证书</td></tr>
                <tr><th>identifier</th><td>标识符。</td></tr>
                <tr><th>secret</th><td>密钥。</td></tr>
                <tr><th>query parameter</th><td>查询参数部分。</td></tr>
                <tr><th>fragment parameter</th><td>分段参数部分。</td></tr>
                <tr><th>SSO</th><td>单点身份认证系统。</td></tr>
                <tr><th>apply</th><td>申请，向SSO管理人员申请开发新的SSO客户端，即应用。</td></tr>
                <tr><th>request</th><td>请求，向服务器发起一个请求。</td></tr>
                <tr><th>SDK</th><td>软件开发包。</td></tr>
              </table>
        </p>
        <p></p>
        <div class="clr"></div>
        <h2 id="API">API 文档</h2>
        <ul style="margin:0 0 0 25px;">
          <li>1.<a href="#OAuth2">OAuth2</a></li>
          <li class="li1">1.1<a href="#authorize">请求授权</a></li>
          <li class="li1">1.2<a href="#token">获取授权</a></li>
          <li>2.<a href="#account">帐号</a></li>
          <li class="li1">2.1<a href="#logout">登出接口</a></li>
          <li>3.<a href="#user">用户</a></li>
          <li class="li1">3.1<a href="#resource">读取接口</a></li>
        </ul>
        <div class="clr"></div>
        <h3 id="OAuth2">OAuth2</h3>
        <p id="authorize" style="font-size:16px;"><strong>请求授权</strong></p>
        <table style="width:100%;" class="list">
			<tr class="row1"><td>authorize</td></tr>
			<tr class="row3"><td>OAuth2的authorize接口</td></tr>
            <tr class="row1"><td>URL</td></tr>
			<tr class="row3"><td><a href="<?php echo $PROJECT_PATH;?>authorize.php"><?php echo $PROJECT_PATH;?>authorize.php</a></td></tr>
            <tr class="row1"><td>HTTP请求方式</td></tr>
			<tr class="row3"><td>GET</td></tr>
            <tr class="row1"><td>请求参数</td></tr>
			<tr class="row3"><td>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="100"></th><th width="40">必选</th><th>说明</th></tr>
                <tr><th>client_id</th><td>true</td><td>客户端标识符</td></tr>
                <tr><th>redirect_uri</th><td>true</td><td>授权回调地址，需与注册客户端里的回调地址一致。</td></tr>
                <tr><th>response_type</th><td>false</td><td>返回类型，支持code、token，默认code。</td></tr>
                <tr><th>state</th><td>false</td><td>用于保持请求和回调的状态，在回调时，会在Query Parameter中回传该参数，当response_type＝token则会在Fragment Parameter回传该参数。</td></tr>
              </table>
            </td></tr>
            <tr class="row1"><td>返回结果</td></tr>
			<tr class="row3"><td>
              <p><span>当response_type为code时</span></p>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="100"></th><th>说明</th></tr>
                <tr><th>code</th><td>用于调用token接口获取授权后的access_token。</td></tr>
                <tr><th>state</th><td>如果传递参数，会回传该参数。</td></tr>
              </table>
              <p><span>当response_type为token时</span></p>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="100"></th><th>说明</th></tr>
                <tr><th>access_token</th><td>用来调用其它接口的授权过的access_token。</td></tr>
                <tr><th>expires_in</th><td>access_token有效期时间，unix的timestamp格式。</td></tr>
                <tr><th>state</th><td>如果传递参数，会回传该参数。</td></tr>
                <tr><th>refresh_token</th><td>如果设置，会返回用来调用获取授权接口的refresh_token。</td></tr>
              </table>
            </td></tr>
            <tr class="row1"><td>示例</td></tr>
			<tr class="row3"><td>
              <p><span>response_type为token时</span><br/>
              //请求<br/>
              <?php echo $PROJECT_PATH;?>authorize.php?client_id=example&redirect_uri=http://www.example.com/response&response_type=token<br/><br/>
              //同意授权后会重定向<br/>
              http://www.example.com/response?access_token=ACCESS_TOKEN_CODE&expires_in=3600
              </p>
              <p><span>response_type为code时</span><br/>
              //请求<br/>
              <?php echo $PROJECT_PATH;?>authorize.php?client_id=example&redirect_uri=http://www.example.com/response&response_type=code<br/><br/>
              //同意授权后会重定向<br/>
              http://www.example.com/response?code=AUTH_CODE
              </p>
            </td></tr>
            <tr class="row1"><td>其他</td></tr>
			<tr class="row3"><td>无</td></tr>
        </table>
        <div class="clr"></div>
        <p id="token" style="font-size:16px;"><strong>获取授权</strong></p>
        <table style="width:100%;" class="list">
			<tr class="row1"><td>token</td></tr>
			<tr class="row3"><td>OAuth2的token接口</td></tr>
            <tr class="row1"><td>URL</td></tr>
			<tr class="row3"><td><a href="<?php echo $PROJECT_PATH;?>token.php"><?php echo $PROJECT_PATH;?>token.php</a></td></tr>
            <tr class="row1"><td>HTTP请求方式</td></tr>
			<tr class="row3"><td>POST</td></tr>
            <tr class="row1"><td>支持格式</td></tr>
			<tr class="row3"><td>JSON</td></tr>
            <tr class="row1"><td>请求参数</td></tr>
			<tr class="row3"><td>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="100"></th><th width="40">必选</th><th>说明</th></tr>
                <tr><th>client_id</th><td>true</td><td>客户端标识符</td></tr>
                <tr><th>client_secret</th><td>true</td><td>客户端密钥</td></tr>
                <tr><th>grant_type</th><td>false</td><td>请求的类型，可以为authorization_code、password、refresh_token，默认authorization_code。</td></tr>
              </table>
              <p></p>
              <p><span>当grant_type为authorization_code时</span></p>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="100"></th><th width="40">必选</th><th>说明</th></tr>
                <tr><th>code</th><td>true</td><td>调用authorize获得的code值。</td></tr>
                <tr><th>redirect_uri</th><td>true</td><td>回调地址，需与注册客户端里的回调地址一致。</td></tr>
              </table>
              <p></p>
              <p><span>当grant_type为password时</span></p>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="100"></th><th width="40">必选</th><th>说明</th></tr>
                <tr><th>username</th><td>true</td><td>授权用户的ID。</td></tr>
                <tr><th>password</th><td>true</td><td>授权用户的密码。</td></tr>
              </table>
              <p></p>
              <p><span>当grant_type为refresh_token时</span></p>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="100"></th><th width="40">必选</th><th>说明</th></tr>
                <tr><th>refresh_token</th><td>true</td><td>grant_type为authorization_code时返回的refresh_token</td></tr>
              </table>
            </td></tr>
            <tr class="row1"><td>返回结果</td></tr>
			<tr class="row3"><td>JSON示例：
              <pre style="border: 1px dashed #819E9C;padding:8px;">
{
    "access_token":"02c932bb9ef889e76070bafe337c5b6e",
    "expires_in":"3600",
    "token_type":"access",
}</pre></td></tr>
            <tr class="row1"><td>字段说明</td></tr>
			<tr class="row3"><td>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="100"></th><th>说明</th></tr>
                <tr><th>access_token</th><td>用来调用其它接口的授权过的access_token。</td></tr>
                <tr><th>expires_in</th><td>access_token有效期时间，unix的timestamp格式。</td></tr>
                <tr><th>token_type</th><td>可选的，token类型</td></tr>
                <tr><th>refresh_token</th><td>可选的，如果设置，会返回用来调用获取授权接口的refresh_token。</td></tr>
              </table>
            </td></tr>
            <tr class="row1"><td>其他</td></tr>
			<tr class="row3"><td>无</td></tr>
        </table>
        <p></p>
        <div class="clr"></div>
        <h3 id="account">帐号</h3>
        <p id="logout" style="font-size:16px;"><strong>登出接口</strong></p>
        <table style="width:100%;" class="list">
			<tr class="row1"><td>logout</td></tr>
			<tr class="row3"><td>登出接口</td></tr>
            <tr class="row1"><td>URL</td></tr>
			<tr class="row3"><td><a href="<?php echo $PROJECT_PATH;?>logout.php"><?php echo $PROJECT_PATH;?>logout.php</a></td></tr>
            <tr class="row1"><td>请求参数</td></tr>
			<tr class="row3"><td>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="100"></th><th width="40">必选</th><th>说明</th></tr>
                <tr><th>access_token</th><td>true</td><td>调用token获得的access_token</td></tr>
                <tr><th>refresh_token</th><td>false</td><td>调用token获得的refresh_token</td></tr>
                <tr><th>redirect_uri</th><td>false</td><td>登出回调地址</td></tr>
                <tr><th>response_type</th><td>false</td><td>redirect_uri存在时有用,返回类型,默认在query形式将结果返回，如果是token时，以fragment形式返回结果</td></tr>
              </table>
            </td></tr>
            <tr class="row1"><td>返回结果</td></tr>
			<tr class="row3"><td>
              <p><span>当redirect_uri存在</span><br/>
              //请求<br/>
              <?php echo $PROJECT_PATH;?>logout.php?access_token=ACCESS_TOKEN_CODE&redirect_uri=http://www.example.com/redirect<br/><br/>
              //登出后会重定向<br/>
              http://www.example.com/redirect?logout=success<br/>
              //如果response_type存在且为token则<br/>
              http://www.example.com/redirect#logout=success
              </p>
              <p><span>当redirect_uri不存在</span><br/>
              //请求<br/>
              <?php echo $PROJECT_PATH;?>logout.php?access_token=ACCESS_TOKEN_CODE<br/><br/>
              //显示是否成功登出<br>
              JSON示例：
        <pre style="border: 1px dashed #819E9C;padding:8px;">{
    "logout":"success"
}
</pre>
              </p>
            </td></tr>
            <tr class="row1"><td>其他</td></tr>
			<tr class="row3"><td>无</td></tr>
        </table>
        <p></p>
        <div class="clr"></div>
        <h3 id="user">用户</h3>
        <p id="resource" style="font-size:16px;"><strong>读取接口</strong></p>
        <table style="width:100%;" class="list">
			<tr class="row1"><td>resource</td></tr>
			<tr class="row3"><td>根据授权用户获取授权用户信息</td></tr>
            <tr class="row1"><td>URL</td></tr>
			<tr class="row3"><td><a href="<?php echo $PROJECT_PATH;?>resource.php"><?php echo $PROJECT_PATH;?>resource.php</a></td></tr>
            <tr class="row1"><td>HTTP请求方式</td></tr>
			<tr class="row3"><td>GET</td></tr>
            <tr class="row1"><td>支持格式</td></tr>
			<tr class="row3"><td>JSON</td></tr>
            <tr class="row1"><td>请求参数</td></tr>
			<tr class="row3"><td>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="100"></th><th width="40">必选</th><th>说明</th></tr>
                <tr><th>access_token</th><td>true</td><td>调用token获得的access_token</td></tr>
              </table>
            </td></tr>
            <tr class="row1"><td>返回结果</td></tr>
			<tr class="row3"><td>JSON示例：
              <pre style="border: 1px dashed #819E9C;padding:8px;">
{
    "uid":"example",
    "username":"示例",
    "role":"学生"
}</pre></td></tr>
            <tr class="row1"><td>字段说明</td></tr>
			<tr class="row3"><td>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="100"></th><th>说明</th></tr>
                <tr><th>uid</th><td>授权用户ID</td></tr>
                <tr><th>username</th><td>授权用户名称</td></tr>
                <tr><th>role</th><td>授权用户是学生还是老师。</td></tr>
              </table>
            </td></tr>
            <tr class="row1"><td>其他</td></tr>
			<tr class="row3"><td>返回结果中的字段属性对应于申请客户端时定义的访问域</td></tr>
        </table>
        <p></p>
        <div class="clr"></div>
        <h2 id="OAuth2.0">OAuth2.0</h2>
        <ul style="margin:0 0 0 25px;">
          <li>1.<a href="#summary">概述</a></li>
          <li>2.<a href="#flow">基本流程</a></li>
          <li>3.<a href="#interface">接口文档</a></li>
          <li>4.<a href="#scene">应用场景</a></li>
          <li class="li1">4.1<a href="#scene-web">Web应用的验证授权</a></li>
          <li class="li1">4.2<a href="#scene-client">客户端应用的验证授权</a></li>
          <li class="li1">4.3<a href="#scene-js">Javascript应用的验证授权</a></li>
          <li>5.<a href="#error">错误码</a></li>
        </ul>
        <p id="summary" style="font-size:16px;"><strong>概述</strong></p>
        <p>OAuth2.0是从2006年开始设计OAuth协议的下一个版本，OAuth2.0同时提供Web，桌面和移动应用程序的支持，并较1.0相比整个授权验证流程更简单更安全。基本流程如下图。</p>
        <p id="flow" style="font-size:16px;"><strong>基本流程</strong></p>
        <img src="../images/document-1.png" width="600" height="344" alt="基本流程">
        <div class="clr"></div>
        <p style="padding-left:20px;"><strong>说明</strong></p>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="60">&nbsp;</th><th>说明</th></tr>
                <tr><th>A</th><td>客户端向授权服务器请求授权。</td></tr>
                <tr><th>B</th><td>客户端得到授权证书</td></tr>
                <tr><th>C</th><td>客户端使用授权证书获取访问令牌。</td></tr>
                <tr><th>D</th><td>客户端得到访问令牌。</td></tr>
                <tr><th>E</th><td>客户端使用访问令牌获取受保护资源。</td></tr>
                <tr><th>F</th><td>客户端得到受保护资源。</td></tr>
              </table>
        <p></p>
        <div class="clr"></div>
        <p>开发者可以先浏览OAuth2.0的接口，熟悉OAuth2的接口及参数的含义，然后我们根据应用场景各自说明如何使用OAuth2.0。</p>
        <p id="interface" style="font-size:16px;"><strong>接口文档</strong></p>
        <p>点<a href="#OAuth2">这里</a></p>
        <p id="scene" style="font-size:16px;"><strong>应用场景</strong></p>
        <p id="scene-web"><span style="font-size:14px;">(1).Web应用的验证授权(Authorization Code)</span></p>
        <p style="padding-left:20px;">1.基本流程：</p>
        <img src="../images/document-2.png" width="600" height="493" alt="基本流程">
        <div class="clr"></div>
        <p style="padding-left:20px;"><strong>说明</strong></p>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="60">&nbsp;</th><th>说明</th></tr>
                <tr><th width="60">&nbsp;</th><th>左A、B、C是用户交互的流程</th></tr>
                <tr><th>左A</th><td>由客户端发起，通过引导用户至授权端点</td></tr>
                <tr><th>左B</th><td>用户输入用户名和密码。</td></tr>
                <tr><th>左C</th><td>用户登录成功进入客户端。</td></tr>
                <tr><th>A</th><td>客户端向授权服务器<a href="#authorize">请求授权</a>，验证客户端标识符、回调地址等，成功引导至授权端点或失败返回错误码。</td></tr>
                <tr><th>B</th><td>用户登录，授权服务器验证用户名和密码。</td></tr>
                <tr><th>C</th><td>用户验证成功引导至回调地址并返回Authorization Code(授权码)或失败用户重新输入。</td></tr>
                <tr><th>D</th><td>客户端使用授权码等证书向授权服务器<a href="#token">获取授权</a>。</td></tr>
                <tr><th>E</th><td>成功返回Access Token(访问令牌)或失败返回错误码。</td></tr>
              </table>
        <p></p>
        <div class="clr"></div>
        <p style="padding-left:20px;">2. 引导需要授权的用户到如下地址：</p><pre style="border: 1px dashed #819E9C;padding:8px;">
<?php echo $PROJECT_PATH;?>authorize.php?client_id=YOUR_CLIENT_ID
&response_type=code&redirect_uri=YOUR_REGISTERED_REDIRECT_URI
&state=1234</pre>
        <p style="padding-left:20px;">3. 如果用户同意授权,页面跳转至 YOUR_REGISTERED_REDIRECT_URI/?code=CODE&state=1234</p>
        <p style="padding-left:20px;">4. 获取Access Token到如下地址：</p><pre style="border: 1px dashed #819E9C;padding:8px;">
<?php echo $PROJECT_PATH;?>token.php?client_id=YOUR_CLIENT_ID
&client_secret=YOUR_CLIENT_SECRET&grant_type=authorization_code
&redirect_uri=YOUR_REGISTERED_REDIRECT_URI&code=CODE</pre>
        <p style="padding-left:20px;">返回值<br/>{ "access_token":"02c932bb9ef889e76070bafe337c5b6e", "expires_in":3600 }</p>
        <p style="padding-left:20px;">5. 使用获得的OAuth2.0 Access Token调用API</p>
        <p></p>
        <div class="clr"></div>
        <p id="scene-client"><span style="font-size:14px;">(2).客户端应用的验证授权（Resource Owner Password Credentials）</span></p>
        <p style="padding-left:20px;">1.基本流程：</p>
		<img src="../images/document-3.png" width="600" height="365" alt="基本流程">
		<!--&nbsp;&nbsp;&nbsp;&nbsp;<br/>-->
        <div class="clr"></div>
        <p style="padding-left:20px;"><strong>说明</strong></p>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="60">&nbsp;</th><th>说明</th></tr>
                <tr><th>A</th><td>客户端接收用户输入的用户名和密码。</td></tr>
                <tr><th>B</th><td>客户端使用户名和密码等证书向授权服务器<a href="#token">获取授权</a>。</td></tr>
                <tr><th>C</th><td>成功返回Access Token(访问令牌)或失败返回错误码。</td></tr>
              </table>
        <p></p>
        <div class="clr"></div>
        <p style="padding-left:20px;">2. 调用</p><pre style="border: 1px dashed #819E9C;padding:8px;">
<?php echo $PROJECT_PATH;?>token.php?client_id=YOUR_CLIENT_ID
&client_secret=YOUR_CLIENT_SECRET&grant_type=password&username=
USER_NAME&password=PASSWORD</pre>
        <p style="padding-left:20px;">返回值<br/>{ "access_token":"02c932bb9ef889e76070bafe337c5b6e", "expires_in":3600 }</p>
        <p style="padding-left:20px;">3. 使用获得的OAuth2.0 Access Token调用API</p>
        <p></p>
        <div class="clr"></div>
        <p id="scene-js"><span style="font-size:14px;">(3).Javascript应用的验证授权(Implicit Grant)</span></p>
        <p style="padding-left:20px;">1.基本流程：</p>
		<img src="../images/document-4.png" width="600" height="684" alt="基本流程">
        <div class="clr"></div>
        <p style="padding-left:20px;"><strong>说明</strong></p>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="60">&nbsp;</th><th>说明</th></tr>
                <tr><th width="60">&nbsp;</th><th>左A、B、F、G是用户交互的流程</th></tr>
                <tr><th>A</th><td>客户端向授权服务器<a href="#authorize">请求授权</a>，验证客户端标识符、回调地址等，成功引导至授权端点或失败返回错误码。</td></tr>
                <tr><th>B</th><td>用户登录，授权服务器验证用户名和密码。</td></tr>
                <tr><th>C</th><td>用户验证成功引导至回调地址并在片段中携带Access Token(访问令牌)或失败用户重新输入。</td></tr>
                <tr><th>D</th><td>JS接收回调地址片段中携带的Access Token(访问令牌)。</td></tr>
                <tr><th>E</th><td>执行脚本解析出Access Token(访问令牌)。</td></tr>
                <tr><th>F</th><td>端点</td></tr>
                <tr><th>G</th><td>客户得到Access Token(访问令牌)。</td></tr>
              </table>
        <p></p>
        <div class="clr"></div>
        <p style="padding-left:20px;">2. 引导需要授权的用户到如下地址：</p><pre style="border: 1px dashed #819E9C;padding:8px;">
<?php echo $PROJECT_PATH;?>authorize.php?client_id=YOUR_CLIENT_ID
&response_type=token&redirect_uri=YOUR_REGISTERED_REDIRECT_URI
&state=1234</pre>
        <p style="padding-left:20px;">3. 如果用户同意授权,页面跳转至 YOUR_REGISTERED_REDIRECT_URI/#access_token=ACCESS_TOKEN&expires_in=3600&state=1234</p>
        <p style="padding-left:20px;">4. 获取页面上的Access Token
        使用javascript获取access_token：</p><pre style="border: 1px dashed #819E9C;padding:8px;">
&lt;script type="text/javascript" &gt;

//解析hash得到access_token值。
var hash = document.location.hash.substring(1);  

&lt;/script&gt;</pre>
        <p style="padding-left:20px;">5. 使用获得的OAuth2.0 Access Token调用API</p>
        <p></p>
        <div class="clr"></div>
        <p id="error" style="font-size:16px;"><strong>错误码</strong></p>
        <p>授权服务器在接收到验证授权请求时，会按照OAuth2.0协议对本请求的请求头部、请求参数进行检验，若请求不合法或验证未通过，授权服务器会返回相应的错误信息，包含以下几个参数：<br/>
        error: 错误码</p>
        <p><span>错误信息的返回方式有三种：</span><br/>
        1. 当请求Authorization Code Endpoint：<?php echo $PROJECT_PATH;?>authorize.php 时出现错误，返回方式是：跳转到redirect_uri,并在uri 的query parameter中附带错误的描述信息。<br/>
        2. 当请求Access Token Endpoint：<?php echo $PROJECT_PATH;?>token.php 时出现错误，返回方式：返回JSON文本。<br/>
        2. 当请求logout Endpoint：<?php echo $PROJECT_PATH;?>logout.php 时出现错误，返回方式是：如果redirect_uri存在，跳转到redirect_uri,并在uri 的query parameter中附带错误的描述信息；如果redirect_uri不存在，返回JSON文本。<br/>
        例如：</p>
        <pre style="border: 1px dashed #819E9C;padding:8px;">{
    "error":"invalid_request"
}
</pre>
        <p><span>OAuth2.0错误响应中的错误码定义如下表所示：</span></p>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="160">错误码</th><th>说明</th></tr>
                <tr><th>invalid_request</th><td>请求不合法</td></tr>
                <tr><th>invalid_client</th><td>client_id或client_secret参数无效</td></tr>
                <tr><th>invalid_grant</th><td>提供的Access Grant是无效的、过期的或已撤销的</td></tr>
                <tr><th>invalid_token</th><td>提供的Access Token是无效的、过期的或已撤销的</td></tr>
                <tr><th>unsupported_response_type</th><td>不支持的 Response Type</td></tr>
                <tr><th>unsupported_grant_type</th><td>不支持的 Grant Type</td></tr>
              </table>
      </div>
      <div class="right">
        <h2>日志<br />
        <span>系统更新记录</span></h2>
        <p><strong>登出系统的BUG修复</strong><br />
          修复了在几个web应用同时使用登录的情况下，第一个web应用登出后，再登出其他web应用时出现invalid_access_token错误的BUG<br />
          <em><span> 12-7-29 - liaiyong</span></em></p>
      </div>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
  <div class="footer">
  <div class="footer_resize">
  <p class="leftt">© Copyright 2011-2012. lixin.edu.cn. All Rights Reserved<br />
    <a href="index.php">首页</a> | <a href="http://www.dcux.com">联系我们</a></p>
    <p class="rightt"><a href="http://www.dcux.com"><strong>Powered by longmeng </strong></a></p>
    <div class="clr"></div>
  <div class="clr"></div>
</div>
</div>
</div>


</body>
</html>
