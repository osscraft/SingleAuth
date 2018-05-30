<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
  <script type="text/javascript" src="/cache/js/admin/stat.js"></script>
  <input id="client_id" type="hidden" value="<?php echo $CLIENT['clientId'];?>"/>
  <div class="body">
    <div class="body_resize">

<p></p>
<div class="text"><h2>客户端访问量</h2></div>
<div class="container">
  <div id="client_date">
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