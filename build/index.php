<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>熊猫文档专注于编程技术</title>
    <link rel="stylesheet" href="/lib/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/dashidan.css">
</head>

<body>

<div style="background: #2196F3;">
    <img src="/img/web_3.png">
</div>

<nav id="nav_bar" class="navbar navbar-expand navbar-light">
    <div class="btn-group" role="group">
        <button type="button" id="nav_btn_index" class="btn btn-primary">首页</button>
        <button type="button" id="nav_btn_ask" class="btn btn-primary">问答</button>
        <button type="button" id="nav_btn_rank" class="btn btn-primary">排行榜</button>
    </div>
    <?php
    if (isset($_SESSION['figure_url'])) {
        echo '<button type="button" id="nav_btn_figure" class="btn btn-success ml-auto"><img id="nav_btn_figure"  class="rounded" src="' . $_SESSION['figure_url'] . '" width="24px" height="24px"></button>';
    } else {
        echo '<button type="button" id="nav_btn_login" class="btn btn-success ml-auto">登录</button>';
    }
    ?>
</nav>

<div id="content_area">

</div>

<script src="/lib/jquery-3.2.1.min.js"></script>
<script src="/lib/bootstrap-4.3.1-dist/js/popper.min.js"></script>
<script src="/lib/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
<script src="/lib/google-code-prettify/run_prettify.js"></script>
<script src="/lib/mustache/mustache.js"></script>

<script src="/js/index.js"></script>
<script src="/js/doc.js"></script>
<script src="/js/rank.js"></script>
<script src="/js/login.js"></script>
<script src="/js/ask.js"></script>
<script src="/js/profile.js"></script>

<script>
    /** 全局变量*/
    /** 当前文档tag*/
    let global_tag = '';
    /** 语言状态*/
    let global_lan = 'zh_cn';
    /** 当前排行*/
    let current_rank_list = '';
    /** 当前文档显示的文章*/
    let global_page = '1.php';

    $(document).ready(function () {
        /** 事件初始化*/
        $('#nav_bar').off('click', 'button', navClickBtn);
        $('#nav_bar').on('click', 'button', navClickBtn);
        /** 加载index框架*/
        let url_index = '/ajax/index.html';
        ajax_get(url_index, indexLoadSuccess);
    });

    /** 导航按钮点击事件*/
    function navClickBtn(e) {
        let btn_id = $(e.target).attr("id");
        active_nav_button(btn_id);

        let url = '';
        switch (btn_id) {
            case 'nav_btn_index':
                url = '/ajax/index.html';
                ajax_get(url, indexLoadSuccess);
                break;
            case 'nav_btn_ask':
                url = '/ajax/ask.html';
                ajax_get(url, askLoadSuccess);
                break;
            case 'nav_btn_rank':
                url = '/ajax/rank.html';
                ajax_get(url, rankLoadSuccess);
                break;
            case 'nav_btn_login':
                url = '/ajax/login.html';
                ajax_get(url, loginLoadSuccess);
                break;
            case 'nav_btn_figure':
                url = '/ajax/profile.html';
                ajax_get(url, loadProfileSuccess);
                break;
        }
    }

    /** 导航按钮状态切换*/
    function active_nav_button(id) {
        $("#nav_btn_index").removeClass("active");
        $("#nav_btn_ask").removeClass("active");
        $("#nav_btn_rank").removeClass("active");
        $("#nav_btn_login").removeClass("active");
        $('#' + id).addClass("active");
    }

    function ajax_post(link_name, data, callback_success) {
        console.log("ajax_post: " + link_name + " data " + data);
        $.ajax({
            type: 'POST',
            url: link_name,
            data: data,
            success: callback_success
        });
    }

    /** 全局ajax_get方法-异步*/
    function ajax_get(url_get, callback_success) {
        console.log('ajax_get: ' + url_get);
        $.ajax({
            url: url_get,
            success: callback_success
        });
    }

    /** 刷新content_area区域内容*/
    function refreshContentArea(content) {
        $("#content_area").html(content);
    }

    /** hbs模板和数据转化为html*/
    function hts2Html(hbs, data) {
        /** 预编译模板*/
        let template = Handlebars.compile(hbs);
        /** 匹配json内容*/
        let html = template(data);
        return html;
    }
</script>
</body>
</html>