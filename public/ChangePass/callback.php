<?php
session_start();

include_once 'config.php';
include_once 'SSOToOAuth2.php';
$oauth = new SSOClient($CFG['SSO_CLIENT_ID'], $CFG['SSO_CLIENT_SECRET']);

if (isset($_REQUEST['code'])) {
    $keys = array();
    $keys['code'] = $_REQUEST['code'];
    $keys['redirect_uri'] = $CFG['SSO_CALLBACK'];
    if(isset($_SESSION['example']['token'])) {
        $token = $_SESSION['example']['token'];
    } else {
        $token = $oauth->getAccessToken('code', $keys );
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SSO Example Callback</title>
</head>
<body>
<?php
if ($token) {
    $_SESSION['example']['token'] = $token;//print_r($token);
	$oauth->access_token=$token['access_token'];
	$result=$oauth->getUserInfo();
	$_SESSION['USER']=$result;
?>
<!--<p>授权完成,<a href="index.php">进入你的页面</a>,<a href="logout.php">退出</a></p>-->
<?php
	Header("Location:index.php");
	exit;
} else {
?>
<p>授权失败。</p>
<?php
}
?>
</body>
</html>
