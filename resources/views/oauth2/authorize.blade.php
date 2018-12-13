<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>OAuth2 Authorize.</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
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
}
    </style>
</head>
<body>
    <header class="navbar navbar-expand navbar-dark flex-column flex-md-row bd-navbar">
        <a class="navbar-brand mr-0 mr-md-2" href="/" aria-label="OAuth2">
            OAuth2
        </a>
    </header>
    <main class="bd-masthead" id="content" role="main">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-3 mx-auto col-md-3 order-md-2">
                    <img class="img-fluid mb-3 mb-md-0" src="http://getbootstrap.com/docs/4.1/assets/img/bootstrap-stack.png" alt="" width="600" height="600">
                </div>
                <div class="col-md-6 order-md-1 text-center text-md-left pr-md-5">
                    <form class="form-layout">
                        <h1 class="mb-3 bd-text-purple-bright">OAuth2</h1>
                        <div class="form-group">
                            <input type="text" class="form-control input-lg" id="username" aria-describedby="username" placeholder="用户名">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control input-lg" id="password" placeholder="密码">
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-success btn-lg btn-block mb15"><span>登录</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>