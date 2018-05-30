<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
  <script type="text/javascript" src="/cache/js/admin/stat.js"></script>
  <input id="client_id" type="hidden" value="<?php echo $CLIENT['clientId'];?>"/>
  <div class="body">
    <div class="body_resize">

<p></p>
<div class="text"><h2>实时在线用户数</h2></div>
<div class="container">
  <div id="online">
  </div>
</div>
<!-- <p></p>
<div class="text"><h2>浏览器分布</h2></div>
<div class="container">
  <div id="browser_top">
  </div>
</div> -->
<p></p>
<div class="text"><h2>浏览器分布</h2></div>
<div class="container">
  <div id="sequence"></div>
  <div id="browser_d3">
  </div>
  <div id="explanation" style="display: none;">
    <span id="percentage">0.314%</span><br>
    of visits begin with this sequence of pages
  </div>
  <div id="sidebar">
    <div id="legend"></div>
  </div>
</div>
<p></p>
<div class="text"><h2>客户端排名</h2></div>
<div class="container">
  <div id="client_top">
  </div>
</div>
<p></p>
<div class="text"><h2>用户登录日志报表</h2></div>
<div class="container">
  <div id="stat">
  </div>
</div>
<p></p>

    </div>
    <div class="clr"></div>
  </div>
<!-- INCLUDE footer.html -->
<?php include dirname(__DIR__) . '/footer.php';?>