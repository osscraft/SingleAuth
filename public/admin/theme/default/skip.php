<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<script type="text/javascript" src="/lib/underscore.js"></script>
<script type="text/javascript" src="/cache/js/adminlib.js"></script>
<script type="text/javascript" src="/cache/js/admin.js"></script>
</head>

<body>
<script type="text/javascript">
$(function() {
    <?php 
    if(!empty($info)) {//要求弹出信息
        $success = empty($success) ? 0 : 1;
        echo "App.Acceptor.inspire(window.name, 'alert', {'cmd':'$cmd', 'args':['$info'], 'success':$success});\n";
    }
    ?>
    <?php 
    if(!empty($data) && is_numeric($data)) {//要求返回
        echo "App.Acceptor.inspire(window.name, 'history.go', {'cmd':'$cmd', 'args':[$data]});\n";
    } else if(!empty($data) && is_string($data)) {//要求跳转
        echo "App.Acceptor.inspire(window.name, 'location.href', {'cmd':'$cmd', 'args':['$data']});\n";
    } else if(!empty($data) && is_array($data)) {//要求callback
        $data = json_encode($data);
        echo "App.Acceptor.inspire(window.name, 'callback', {'cmd':'$cmd', 'data':$data});\n";
    }
    ?>
});
</script>
</body>
</html>
