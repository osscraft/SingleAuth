<?php if(!empty($REQUEST_ERROR)) {?>
<!-- INCLUDE header.html -->
   <div class="body">
    <div class="body_resize">
      <p><?php echo $REQUEST_ERROR;?></p>
    </div>
    <div class="clr"></div>
  </div>
<!-- INCLUDE footer.html -->
<?php } else {?>
<?php include dirname(dirname(dirname(__DIR__))) . '/theme/default/error.php';?>
<?php }?>
