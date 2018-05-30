<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
   <div class="body">
    <div class="body_resize">

<p></p>
<div class="text"><h2>设置首页皮肤</h2></div>
<div class="container">
  <form action="skin.php?key=modify" method="post">
  <div id="skin_main">
    <?php foreach ($mains as $m) {?>
    <label class="cb-radio cb-lg" style="display: inline-block;">
        <input class="skin-main" type="radio" name="skin-main"<?php echo $m == $main ? ' checked' : '';?> value="<?php echo "$m";?>"><?php echo "$m";?>
    </label>
    <?php }?><br>
    <button type="button" class="skin-main btn btn-primary" style="margin: 6px 0 0 6px;" onclick="submitSkin(this)">确定</button>
  </div>
  </form>
</div>
<p></p>
<div class="text"><h2>设置管理皮肤</h2></div>
<div class="container">
  <form action="skin.php?key=modify" method="post">
  <div id="skin_admin">
    <?php foreach ($admins as $a) {?>
    <label class="cb-radio cb-lg" style="display: inline-block;">
        <input class="skin-admin" type="radio" name="skin-admin"<?php echo $a == $admin ? ' checked' : '';?> value="<?php echo "$a";?>"><?php echo "$a";?>
    </label>
    <?php }?><br>
    <button type="button" class="skin-admin btn btn-primary" style="margin: 6px 0 0 6px;" onclick="submitSkin(this)">确定</button>
  </div>
  </form>
</div>
<p></p>

   </div>
    <div class="clr"></div>
  </div>
<!-- INCLUDE footer.html -->
<?php include dirname(__DIR__) . '/footer.php';?>