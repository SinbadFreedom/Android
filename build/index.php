<?php
   session_start();
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
    <img src="/img/web_3.png">
</div>

<nav class="navbar navbar-expand navbar-light">
    <div class="container">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link active" href="/index.php"><b>首页</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/php/forum/index.php"><b>笔记</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/php/rank_list.php"><b>排行榜</b></a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <?php
                if (isset($_SESSION['figure_url'])) {
                    echo '<a class="nav-link" href="/php/user_info.php"><img class="rounded" src="' . $_SESSION['figure_url'] . '" width="24px" height="24px"></a>';
                } else {
                    echo '<a class="nav-link" href="/php/login_ui.php"><b>登录</b></a>';
                }
                ?>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="text-center">
        <a href="/python3.7.2/zh_cn/catalog.php"><h2><br>Python3.7.2<br><br></h2></a>
        <a href="/java_basic/zh_cn/catalog.php"><h2><br>Java基础教程<br><br></h2></a>
        <a href="/mongo/zh_cn/catalog.php"><h2><br>Mongo操作手册<br><br></h2></a>
        <a href="/php/zh_cn/catalog.php"><h2><br>PHP中文手册<br><br></h2></a>
    </div>
</div>

<div class="container fixed-bottom text-right">
    <small>天津码桥科技有限公司出品</small>
    <small>津ICP备19002572号-1</small>
</div>

<script src="/lib/jquery-3.2.1.min.js"></script>
</body>
</html>;