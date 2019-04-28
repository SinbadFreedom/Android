<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <title>熊猫文档-面向程序员的文档站</title>
    <link rel="stylesheet" href="lib/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dashidan.css">
</head>

<body>

<div style="background: #438cf9">
    <img src="img/web_1.png">
</div>
<hr>
<div class="col-sm-6 col-md-4 col-lg-4 text-center">
    <a href="python3.7.2/zh_cn/catalog.php"><h2><br>Python3.7.2<br><br></h2></a>
</div>

<h4>最新笔记</h4>

<div id="note_area">
    <!-- 评论区-->
</div>

<script src="lib/jquery-3.2.1.min.js"></script>
<script>
    /** 评论*/
    var url = "php/forum/index.php";
    $.ajax({
        url: url,
        type: "GET",
        async: false,//同步请求用false,异步请求true
        dataType: "html",
        data: {},
        success: function (data) {
            document.getElementById("note_area").innerHTML = data;
        },
        error: function (data, textstatus) {
            //请求不成功返回的提示
        }
    });
</script>

</body>
<footer>津ICP备19002572号-1</footer>
</html>