<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>应用开发</title>
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
          <li><a href="index.php" class="active"><span>首页</span></a></li>
          <!--<li><a href="development.php" class="active"><span>应用开发</span></a></li>-->
          <li><a href="wiki.php"><span>文档</span></a></li>
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
      <h2>应用开发</h2>
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
        <h2>新手引导</h2>
        <p><span>目录</span></p>
        <ul style="margin:0 0 0 25px;">
          <li>1.<a href="#flow">应用开发流程图</a></li>
          <li>2.<a href="#create">创建应用</a></li>
          <li>3.<a href="#develop">技术开发</a></li>
          <li>4.<a href="#manual">开发指南</a></li>
          <li>5.<a href="wiki.php#explain">名词解释</a></li>
        </ul>
        <div class="clr"></div>
        <h3 id="flow">应用开发流程图</h3>
        <p><strong>示意图</strong></p>
        <img src="../images/development-1.png" alt="应用开发流程示意图" width="523" height="446" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/>
        <div class="clr"></div>
        <p><strong>说明</strong></p>
              <table class="param" cellspacing="0" cellpadding="0" width="100%">
                <tr><th width="60">&nbsp;</th><th>说明</th></tr>
                <tr><th>A</th><td>申请开发应用，请向SSO系统管理人员发出申请。申请时请提供应用类型(client_type)或有应用回调URI(redirect_uri)等信息。</td></tr>
                <tr><th>B</th><td>申请成功提供开发者client indentifier。</td></tr>
                <tr><th>C</th><td>如果开发应用工具为PHP、Java请向存放相应SDK的服务器发送下载请求，详情请见<a href="download.php#SDK">这里</a>。</td></tr>
                <tr><th>D</th><td>下载PHPSDK、JavaSDK或更多。</td></tr>
                <tr><th>E</th><td>将申请得到的client indentifier或有SDK包含入应用中，开始进行相关的技术开发工作。</td></tr>
                <tr><th>1</th><td>开发个人或开发团队。</td></tr>
                <tr><th>2</th><td>SSO管理系统，用于管理SSO客户端和SSO所需或产生的数据信息。</td></tr>
                <tr><th>3</th><td>下载SDK的服务器。</td></tr>
                <tr><th>4</th><td>开发者根据不同<a href="wiki.php#scene">应用场景</a>开发、发布应用程序。</td></tr>
              </table>
        <p></p>
        <div class="clr"></div>
        <h3 id="create">创建应用</h3>
        <p>开发者与校方达成开发协议，申请开发应用时，由SSO系统管理人员提供开发新应用（客户端）的client indentifier（client_id、client_secret）及其他。</p>
        <div class="clr"></div>
        <h3 id="develop">技术开发</h3>
        <p>根据申请获得的client indentifier开始进行相关的技术开发工作。基中client_id是应用的唯一标识，平台通过client_id来鉴别应用的身份；client_secret是给应用分配的密钥，开发者需要妥善保存这个密钥，这个密钥用来保证应用来源的的可靠性，防止被伪造。<br/>
        参考：<a href="wiki.php">文档</a>、<a href="download.php#SDK">SDK下载</a>、<a href="support.php">支持</a>。
        </p>
        <div class="clr"></div>
        <h3 id="manual">开发指南</h3>
        <p><strong>SDK简介及作用</strong><br/>
        SDK是特定语言实现的一个可以通用的API使用的工具，SDK实现了SSO平台的全部或大部分接口，以便开发者不用关心API接口细节，认证实现等，可以直接调用接口完成特定的功能。支持平台接口的SDK语言包括java,php等，具体请参考<a href="download.php#SDK">SDK</a>列表页面
        </p>
        <p><strong>如何通过授权访问数据</strong><br/>
        平台支持OAuth2.0认证方式.<br/>
        OAuth2.0协议为用户资源的授权提供了一个安全的、开放而又简易的标准。<br/>
        关于OAuth2.0协议可以参考 <a href="http://oauth.net/2" target="_blank">http://oauth.net/2</a><br/>
        使用OAuth2.0认证来获取数据介绍详细见：<a href="wiki.php#OAuth2.0">Oauth2.0</a>
        </p>
        <div class="clr"></div>
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
