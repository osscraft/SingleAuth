<!-- INCLUDE header.html -->
<?php include dirname(__DIR__) . '/header.php';?>
   <div class="body">
    <div class="body_resize">

<p></p>
<div align="center">
    <!-- IF SUCCESS -->
    <?php if(!empty($SUCCESS)) {?>
	<h2>修改成功！</h2>
    <!-- ELSE -->
    <?php } else {?>
	<h2>修改失败！</h2>
    <!-- ENDIF -->
    <?php }?>
	<p><a href="javascript:history.go(-1);">返回</a></p>
</div>
<p></p>

   </div>
    <div class="clr"></div>
  </div>
<!-- INCLUDE footer.html -->
<?php include dirname(__DIR__) . '/footer.php';?>