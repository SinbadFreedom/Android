<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <title>熊猫文档-面向程序员的文档站</title>
    <link rel="stylesheet" href="/lib/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/dashidan.css">
</head>

<body>

<div style="background: #2196F3">
    <img src="/img/web_1.png">
</div>

<nav class="navbar navbar-expand navbar-light">
    <div class="container">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/index.php"><b>首页</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/php/forum/index.php"><b>笔记</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/php/rank_list.php"><b>排行榜</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="/php/login.php"><b>登录</b></a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <div align="center" style="margin-top: 3em">
        <button onclick="qq_login()"><img src="/img/Connect_logo_5.png"></button>
    </div>
</div>

<script>
    function qq_login() {
        window.location.href = "/lib/Connect2.1/example/oauth/index.php";
    }
</script>
</body>
</html>