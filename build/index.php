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
                <a class="nav-link active" href="/index.php"><b>首页</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/php/forum/index.php"><b>笔记</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/php/rank_list.php"><b>排行榜</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/php/login.php"><b>登录</b></a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="col-4">
        <a href="/python3.7.2/zh_cn/catalog.php"><h2><br>Python3.7.2<br><br></h2></a>
    </div>
</div>

<div class="container fixed-bottom text-right">
    <small>天津码桥科技有限公司出品</small> <small>津ICP备19002572号-1</small>
</div>

<script src="/lib/jquery-3.2.1.min.js"></script>
<script>
    // /** 评论*/
    // var url = "php/forum/index.php";
    // $.ajax({
    //     url: url,
    //     type: "GET",
    //     async: false,//同步请求用false,异步请求true
    //     dataType: "html",
    //     data: {},
    //     success: function (data) {
    //         document.getElementById("note_area").innerHTML = data;
    //     },
    //     error: function (data, textstatus) {
    //         //请求不成功返回的提示
    //     }
    // });

    // /** 排行榜*/
    // var url = "php/rank_list.php";
    // $.ajax({
    //     url: url,
    //     type: "GET",
    //     async: false,//同步请求用false,异步请求true
    //     dataType: "html",
    //     data: {},
    //     success: function (data) {
    //         document.getElementById("rank_list").innerHTML = data;
    //     },
    //     error: function (data, textstatus) {
    //         //请求不成功返回的提示
    //     }
    // });
</script>

<!--<script>-->
<!--    function qq_login()-->
<!--    {-->
<!--        window.location.href = "lib/Connect2.1/example/oauth/index.php";-->
<!--    }-->
<!--</script>-->
</body>
</html>