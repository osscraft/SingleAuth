<!-- INCLUDE header.html -->
<?php include __DIR__ . '/header.php';?>
   <div class="body">
    <div class="body_resize">
      <div class="left">
        <h2>概述<br />
        <span></span></h2>
        <p><strong>SSO管理系统是什么？</strong><br />
        SSO管理系统是一个具体的SSO客户端，采用了SSO认证。</p>
        <p><strong>SSO管理系统做什么？</strong><br />
        SSO管理系统用于管理SSO所需和产生的数据信息，包括客户端、LDAP服务器配置、<!--授权信息、-->管理系统的用户等。</p>
        <p><strong>SSO管理系统怎么用？</strong><br />
        首先需要登录，登录后才能进入“客户端管理”、“LDAP配置管理”、<!--“授权信息管理”、-->“用户管理”等。系统启用后，首次登录时会提示将本用户设为管理员。<br/>“客户端管理”、“LDAP配置管理”、<!--“授权信息管理”、-->“用户管理”具体说明见下文。</p>
        <div class="clr"></div>
        <h2>客户端管理<br />
        <span>管理所有SSO授权客户端</span></h2>
        <p>管理所有授权客户端，可以新增、修改、删除客户端，每一个客户端都有相应的“客户端标识符”、“客户端密钥”、“客户端名称”、“客户端类型”、“客户端描述”、“资源访问域”、“客户端地址”、“客户端LOGO路径”、“在首页显示”等。</p>
        <ul style="margin:0 0 0 25px;">
          <li>客户端标识符：是唯一的，不重复。</li>
          <li>客户端密钥：验证客户端合法性。</li>
          <li>客户端名称：客户端的名称。</li>
          <li>客户端类型：标识了是web应用、桌面应用还是其他等等。</li>
          <li>客户端描述：即为描述客户端。</li>
          <li>资源访问域：限定了客户端的访问资源的范围，如“uid,username,role”，即客户端只能获得授权用户的uid、username和role的值。</li>
          <li>客户端地址：客户端的网络地址，即在门户页中图标点击跳向的地址。为了实现直接跳转至统一登录界面，此地址设置为客户端请求授权地址，见<a href="http://sso.lixin.edu.cn/wiki.php#authorize" target="_blank">这里</a>。</li>
          <li>客户端LOGO路径：客户端LOGO图标的路径，即在门户页中图标的地址。</li>
          <li>在首页显示：标记是否在首页中显示。</li>
        </ul>
        <div class="clr"></div>
        <h2>LDAP配置管理<br />
        <span>管理SSO用户资源服务器配置，即LDAP配置</span></h2>
        <p>设置LDAP配置，包括“HOST”、“BASE DN”、<!--“RESOURCE DN”、-->“ROOT DN”、“ROOT PW”等。</p>
        <ul style="margin:0 0 0 25px;">
          <li>HOST：地址</li>
          <li>BASE DN：基本DN</li>
          <!--<li>RESOURCE DN：要检索资源信息的DN</li>-->
          <li>ROOT DN：admin用户登录时DN</li>
          <li>ROOT PW：admin用户登录时密码</li>
        </ul>
        <div class="clr"></div>
        <!--<h2>授权信息管理<br />
        <span>管理SSO所有授权记录</span></h2>
        <p>管理所有授权记录，每一条记录包含“客户端标识符”，“授权用户ID”，“授权时间”等。</p>
        <ul style="margin:0 0 0 25px;">
          <li>客户端标识符：对应于客户端管理里的标识符。</li>
          <li>授权用户ID：授权用户的uid。</li>
          <li>授权时间：授权用户通过SSO授权给客户端时的时间。</li>
        </ul>
        <div class="clr"></div>-->
        <h2>用户管理<br />
        <span>管理本系统的用户</span></h2>
        <p>用户管理，是指管理本管理系统中的用户。每一个用户包含“用户ID”、“用户名称”、“管理员”等</p>
        <ul style="margin:0 0 0 25px;">
          <li>用户ID：管理系统用户的uid，对应于授权用户的uid。</li>
          <li>用户名称：管理系统用户名称，可与授权用户的username不相同。</li>
          <li>管理员：标识是否是管理员。</li>
        </ul>
      </div>
      <div class="right">
        <h2>日志<br />
          <span>系统更新记录</span></h2>
        <p><strong>更新页面</strong><br />
          使用了新的页面模板文件，页面相对美观多了<br />
          <em><span> 12-7-25 - xuexiaowei</span></em><br />
        </p>
        <p><strong>首次登录设置用户</strong><br />
          实现系统首次登录时，提示将本用户设为管理员<br />
          <em><span> 12-7-22 - liaiyong</span></em><br />
        </p>
      </div>
      <div class="clr"></div>
    </div>
    <div class="clr"></div>
  </div>
<!-- INCLUDE footer.html -->
<?php include __DIR__ . '/footer.php';?>
