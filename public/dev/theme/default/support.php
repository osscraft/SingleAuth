<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>支持</title>
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
          <li><a href="wiki.php"><span>文档</span></a></li>
          <li><a href="download.php"><span>下载</span></a></li>
          <li><a href="support.php" class="active"><span>支持</span></a></li>
        </ul>
      </div>
      <div class="clr"></div>
    </div>
  </div>
  <div class="slider_top">
    <div class="header_text2">
      <!--<a href="#"><img src="../images/Sing_up.gif" alt="picture" border="0" /></a>-->
      <h2>支持</h2>
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
        <h2>常见问题</h2>
        <p><span>目录</span></p>
        <ul style="margin:0 0 0 25px;">
          <li>1.<a href="#application">应用相关</a></li>
          <li>2.<a href="#interface">接口相关</a></li>
          <li>3.<a href="#other">其他</a></li>
        </ul>
        <div class="clr"></div>
        <h3 id="application">应用相关</h3>
        <p><strong>1、如果 client_id 或 client_secret 丢失怎么办？</strong><br />
        您可以询问系统管理员，在应用管理后台查询到。</p>
        <p><strong>2、请问 client_id 的有效期是多长时间？</strong><br />
        理论上无限期，系统管理员有权将 client_id 失效。</p>
        <p><strong>3、应用类型变更的问题。如果应用已经在使用，想改变应用类型怎么办？</strong><br />
        应用分类无法变更，请开发者慎重选择。</p>
        <p><strong>4、你好, 想请问要怎样才能再次查看我应用的 client_id 和 client_secret？</strong><br />
        请询问系统管理员，在应用管理后台查询到。</p>
        <h3 id="interface">接口相关</h3>
        <p><strong>1、我想实现xxx功能，请问要用哪个接口？</strong><br />
        请您在<a href="wiki.php#API">API文档</a>查询对应接口。</p>
        <p><strong>2、client_id 和 client_secret 怎么用？</strong><br />
        请按OAuth2协议传递 client_id 和 client_secret。</p>
        <p><strong>3、如何通过调用接口取消用户对一个应用的OAUTH授权？</strong><br />
        目前还没有支持的接口。</p>
        <p><strong>4、登出接口调用时不起作用，怎样实现当前用户退出登录？</strong><br />
        目前这个接口仅支持web应用场合，类似JS、PHP可以正常调用。</p>
        <p><strong>5、url参数和返回值怎么编码，为什么我的返回值一直报错？</strong><br />
        url参数必须使用UTF8编码，返回结果都使用UTF8编码。</p>
        <p><strong>6、平台支持哪些语言？</strong><br />
        平台使用HTTP协议，与语言无关。 为了方便开发，我们提供了部分语言的SDK。</p>
        <p><strong>6、同一个浏览器授权多个账号的问题？</strong><br />
        可以用接口logout.php，当前用户退出登录。</p>
        <p><strong>7、请问 Access Token 的有效期是多久？</strong><br />
        OAuth2.0应用有效期为1小时。</p>
        <p><strong>8、怎么申请Refresh Token？</strong><br />
        暂不开放。</p>
        <h3 id="other">其他</h3>
        <p>具体问题我们会提供一对一解决的方法。</p>
        <p><span>您可以通过以下方式联系龙盟科技：</span><br/>
          <strong>联系地址：</strong>上海市.宝城路.158号48栋<br/>
          <strong>联系电话：</strong>021-54152057，021-54152067<br/>
          <strong>传 真：</strong>021-54152067<br/>
          <strong>电子邮件：</strong>contact@dcux.com
        </p>
      </div>
      <div class="right">
        <h2>日志<br />
        <span>系统更新记录</span></h2>
        <p><strong>登出系统的BUG修复</strong><br />
          修复了在几个web应用同时使用登录的情况下，第一个应用登出后，再登出其他应用时出现invalid_access_token错误的BUG<br />
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
