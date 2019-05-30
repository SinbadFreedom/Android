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
    /** 创建问答的tag*/
    let global_new_ask_tag = '';

    $(document).ready(function () {
        /** 事件初始化*/
        $('#nav_bar').off('click', 'button', navClickBtn);
        $('#nav_bar').on('click', 'button', navClickBtn);
        /** 加载index框架*/
        let url_index = '/ajax/index.html';
        ajax_get(url_index, indexLoadSuccess);
    });

    function btn_click(e) {
        let btn_id = $(e.target).attr("id");
        console.log(' btn_click ' + btn_id);
        let c_index = btn_id.indexOf("_");
        let type = btn_id.slice(0, c_index);
        /** 跳过‘_’*/
        let param = btn_id.slice(c_index + 1);
        switch (type) {
            case 'new':
                /** 新建笔记*/
                clickBtnNewNote(param);
                break;
            case 'newAsk':
                /** 新建笔记提交页*/
                clickBtnNewNoteCommit();
                break;
            default:
                console.log('click default ' + btn_id);
                break;
        }
    }

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

    /** 点击创建新笔记按钮*/
    function clickBtnNewNote(param) {
        global_new_ask_tag = param;
        ajax_get_url('/ajax/newAsk.html');
    }

    /** 点击提交笔记按钮*/
    function clickBtnNewNoteCommit() {
        let title = $("#newAsk_title").val();
        let content = $("#newAsk_content").val();
        /** 这里是提交表单前的非空校验*/
        if (global_new_ask_tag === "" || !global_new_ask_tag) {
            alert("请选择标签");
            /** 阻止表单提交*/
            return false;
        }

        if (title === "" || !title) {
            alert("请输入标题");
            /** 阻止表单提交*/
            return false;
        }

        if (content === "" || !content) {
            alert("请输入内容");
            /** 阻止表单提交*/
            return false;
        }

        let data = {};
        data.ask_tag = global_new_ask_tag;
        data.title = title;
        data.content = content;
        ajax_post('/php/forum/ask_new_summit.php', data);
    }

    /** 导航按钮状态切换*/
    function active_nav_button(id) {
        $("#nav_btn_index").removeClass("active");
        $("#nav_btn_ask").removeClass("active");
        $("#nav_btn_rank").removeClass("active");
        $("#nav_btn_login").removeClass("active");
        $('#' + id).addClass("active");
    }

    /** ajax异步获取url数据，设置到指定的id区域默认id为content_area*/
    function ajax_get_url(link_name, content_area_id = 'content_area') {
        console.log("ajax_get_url: " + link_name);
        $.ajax({
            url: link_name,
            success: function (res) {
                $("#" + content_area_id).html(res);
                /** 先清空所有绑定事件，否则会重复调用*/
                if (link_name === '/ajax/newAsk.html') {
                    /** 修改标签内容*/
                    $('#newAsk_tag').text(global_new_ask_tag);
                }
            }
        });
    }

    function ajax_post(link_name, data) {
        console.log("ajax_post: " + link_name);
        $.ajax({
            type: 'POST',
            url: link_name,
            data: data,
            success: function (res) {
                console.log("ajax_post " + res);
                if (res.state !== 0) {
                    console.log("ajax_post res.state !== 0 " + res.state);
                    return;
                }

                switch (link_name) {
                    case '/php/forum/ask_new_summit.php':
                        /** 提交笔记成功会跳转到笔记页面*/
                        let url = '/php/forum/ask_get.php?tag=' + res.tag + '&contentid=' + res.content_id;
                        ajax_get_url(url);
                        break;
                }
            }
        });
    }

    /** 全局ajax_get方法*/
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

</script>
</body>
</html>