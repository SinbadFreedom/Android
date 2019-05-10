<?php
session_start();

/** 获取完整的来路URL 记录session,登陆完成后跳转回去*/
$url = $_SERVER["HTTP_REFERER"];
$_SESSION['from_url'] = $url;

?>
<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item active">
            <?php
            if (isset($_SESSION['figureurl_qq'])) {
                echo '<a class="nav-link" href="/php/user_info.php"><img class="rounded" src="' . $_SESSION['figureurl_qq'] . '" width="24px" height="24px"></a>';
            } else {
                echo '<a class="nav-link " href="/php/login_ui.php"><b>登录</b></a>';
            }
            ?>
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