<?php
session_start();
?>
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
                <a class="nav-link active" href="/php/forum/index.php"><b>笔记</b></a>
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
                    echo '<a class="nav-link " href="/php/login_ui.php"><b>登录</b></a>';
                }
                ?>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <form action="note_new_summit.php" method="post" id="note_new">
        <div class="form-group">
            <label for="note_tag">标签:</label>
            <input type="text" readonly class="form-control-plaintext" id="note_tag" name="note_tag" value="标签">
        </div>
        <div class="form-group">
            <label for="title">标题:</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="请输入标题">
        </div>
        <div class="form-group">
            <label for="content">内容:</label>
            <textarea class="form-control" id="content" name="content" rows="5" placeholder="请输入内容"></textarea>
        </div>
        <?php
        if (isset($_SESSION['figure_url'])) {
            echo '<button type="submit" class="btn btn-primary ml-auto">提交</button>';
        } else {
            echo '<label class="btn btn-warning ml-auto">登录后方可提交</label>';
        }
        ?>
    </form>
</div>

<script src="/lib/jquery-3.2.1.min.js"></script>

<script>
    function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return decodeURI(r[2]);
        return null;
    }

    var tag = getUrlParam('tag');
    $('#note_tag').attr('value', tag);
</script>
<script>
    $("#note_new").submit(function () {
        var tag = $("#note_tag").attr('value');
        var title = $("#title").val();
        var content = $("#content").val();
        /** 这里是提交表单前的非空校验*/
        if (tag === "" || !tag) {
            alert("请选择标签");
            return false;/*阻止表单提交*/
        }

        if (title === "" || !title) {
            alert("请输入标题");
            return false;/*阻止表单提交*/
        }

        if (content === "" || !content) {
            alert("请输入内容");
            return false;/*阻止表单提交*/
        }

        return true;
    });
</script>
</body>
</html>