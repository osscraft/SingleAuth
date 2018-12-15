<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>OAuth2 Authorize.</title>
    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="//cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="//code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <style>
.input-lg {
    font-size: 13px;
    padding: 10px 16px;
    height: 46px;
    line-height: 1.3333333;
}
.form-layout .btn-lg {
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
    border-color: #25a25a;
}
    </style>
    <script>
        var ws = new WebSocket("{{$form->socketServerUri}}");
        ws.onopen = function() {
            console.log("连接成功");
            // ws.send('tom');
            // console.log("给服务端发送一个字符串：tom");
        };
        ws.onmessage = function(e) {
            console.log("收到服务端的消息：" + e.data);
            var json = JSON.parse(e.data);
            var event = json.event;
            var data = json.data;
            switch (event) {
                case 'onconnect':
                    clientId = data.client_id;
                    break;
            }
            if(event != 'onticks') {
                $('#messages').append('<div class=message>' + '[' + (new Date()).toLocaleString() + '] ' + e.data + '</div>');
            }
        };
    </script>
</head>
<body>
    <header class="navbar navbar-expand navbar-dark flex-column flex-md-row bd-navbar bg-success">
        <a class="navbar-brand mr-0 mr-md-2" href="/" aria-label="OAuth2">
            OAuth2
        </a>
    </header>
    <main class="bd-masthead" id="content" role="main">
        <div class="container">
            <div class="row pt-md-5">
                <div class="row mx-auto align-items-center">
                    <div class="col-6 mx-auto col-md-6">
                        <img class="img-fluid mb-3 mb-md-0" src="http://getbootstrap.com/docs/4.1/assets/img/bootstrap-stack.png" alt="" width="600" height="600">
                    </div>
                    <div class="col-md-6 text-center text-md-left pr-md-5">
                        <form action="" method="POST" class="form-layout" enctype="multipart/form-data">
                            <h1 class="mb-3 bd-text-purple-bright">OAuth2</h1>
                            <div class="form-group">
                                <input type="text" class="form-control input-lg" id="username" aria-describedby="username" placeholder="用户名">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control input-lg" id="password" placeholder="密码">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-lg btn-block mb15"><span>登录</span></button>
                            </div>
                            <div class="form-group">
                                <label>忘记密码？</label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row mx-auto align-items-center pt-md-5">
                <div class="mx-auto">
                    <div class="mx-auto text-center"><label>二维码登录</label></div>
                    <img class="img-fluid mb-3 mb-md-0" src="{{URL('/qrcode/fac0000000000000001')}}" alt="" width="300" height="300">
                </div>
            </div>
        </div>
    </main>
</body>
</html>