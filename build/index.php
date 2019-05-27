<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>熊猫文档-面向程序员的技术文档网站</title>
    <link rel="stylesheet" href="/lib/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/dashidan.css">
</head>

<body>

<div style="background: #2196F3">
    <img src="/img/web_3.png">
</div>

<nav class="navbar navbar-expand navbar-light">
    <div class="btn-group" role="group">
        <button type="button" id="nav_btn_index" class="btn btn-primary">首页</button>
        <button type="button" id="nav_btn_forum" class="btn btn-primary">笔记</button>
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

<script>
    /** 全局变量*/
    let current_tag = '';
    let current_lan = 'zh_cn';
    let current_rank_list = '';
    let current_doc = 'catalog.html';
    let global_new_note_tag = '';

    $(document).ready(function () {
        /** button点击事件*/
        $('button').click(btn_click);
        let url = '/ajax/index.html';
        ajax_get_url(url);

        /** 超链接点击处理*/
        $('a').click(a_click);

        /** 更新语言按钮状态*/
        active_language_btn(current_lan);
    });

    function btn_click(e) {
        let btn_id = $(e.target).attr("id");
        let c_index = btn_id.indexOf("_");
        let type = btn_id.slice(0, c_index);
        /** 跳过‘_’*/
        let param = btn_id.slice(c_index + 1);
        switch (type) {
            case 'nav':
                getPageIndex(btn_id);
                break;
            case 'tag':
                getPageTag(param);
                break;
            case 'rank':
                getPageRank(btn_id);
                break;
            case 'note':
                getNoteList(btn_id, param);
                break;
            case 'doc':
                clickBtnClick(btn_id);
                break;
            case 'new':
                clickBtnNewNote(param);
                break;
            case 'newNote':
                clickBtnNewNoteCommit();
                break;
            default:
                console.log('click default ' + btn_id);
                break;
        }
    }

    /** a标签点击 目前只有目录链接和文章中内的内容采用a标签*/
    function a_click(e) {
        e.preventDefault();
        let a_url = $(this).attr("href");
        // console.log('a_url 1: ' + a_url);
        a_url = '/' + current_tag + '/' + current_lan + '/' + a_url;
        console.log('a_url 2: ' + a_url);
        ajax_get_url(a_url, 'doc_content');
    }

    /** 导航按钮点击事件*/
    function getPageIndex(id) {
        let url = '';
        switch (id) {
            case 'nav_btn_index':
                url = '/ajax/index.html';
                break;
            case 'nav_btn_forum':
                url = '/ajax/note_tag.html';
                break;
            case 'nav_btn_rank':
                url = '/ajax/rank_list.html';
                break;
            case 'nav_btn_login':
                url = '/php/login_ui.php';
                break;
            case 'nav_btn_figure':
                url = '/php/user_info.php';
                break;
        }

        active_nav_button(id);
        ajax_get_url(url);
    }

    /** 文章tag按钮点击*/
    function getPageTag(tag) {
        current_tag = tag;
        let url = '/ajax/doc.html';
        /** 设置current_tag变量*/
        ajax_get_url(url);
    }

    /** 排行榜按钮点击*/
    function getPageRank(type) {
        let url = '/php/rank_list.php?type=' + type;
        ajax_get_url(url, 'rank_list_info');
        active_rank_list_button(type);
    }

    function getNoteList(btn_id, tag) {
        console.log('getNoteList ' + tag + ' btn_id ' + btn_id);
        let url = '/php/forum/index.php?tag=' + tag;
        ajax_get_url(url, 'note_tag_info');
        active_note_tag_button(btn_id);
    }

    function clickBtnClick(btn_id) {
        switch (btn_id) {
            case 'doc_zh_cn':
                /** 中文*/
                current_lan = 'zh_cn';
                /** 加载文档标题区*/
                ajax_get_url('/' + current_tag + '/' + current_lan + '/catalog.php', 'doc_content');
                active_language_btn(current_lan);
                break;
            case 'doc_en':
                /** 英文*/
                current_lan = 'en';
                /** 加载文档标题区*/
                ajax_get_url('/' + current_tag + '/' + current_lan + '/catalog.php', 'doc_content');
                active_language_btn(current_lan);
                break;
        }
    }

    /** 点击创建新笔记按钮*/
    function clickBtnNewNote(param) {
        global_new_note_tag = param;
        ajax_get_url('/ajax/newNote.html');
    }

    /** 点击提交笔记按钮*/
    function clickBtnNewNoteCommit() {
        let title = $("#newNote_title").val();
        let content = $("#newNote_content").val();
        /** 这里是提交表单前的非空校验*/
        if (global_new_note_tag === "" || !global_new_note_tag) {
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
        data.note_tag = global_new_note_tag;
        data.title = title;
        data.content = content;
        ajax_post('/php/forum/note_new_summit.php', data);
    }

    /** 导航按钮状态切换*/
    function active_nav_button(id) {
        $("#nav_btn_index").removeClass("active");
        $("#nav_btn_forum").removeClass("active");
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
                $('button').unbind();
                /** 再绑定新加载页面的click事件，统一处理，否则侦听不到*/
                $('button').bind("click", btn_click);

                $('a').unbind();
                $('a').bind("click", a_click);

                if (link_name === '/ajax/rank_list.html') {
                    /** 加载排行榜html页面完成，加载今日排行榜信息*/
                    getPageRank('rank_1');
                } else if (link_name === '/ajax/note_tag.html') {
                    /** 加载笔记tag html页面完成，加载笔记内容*/
                    getNoteList('note_content_all');
                } else if (link_name === '/ajax/doc.html') {
                    /** 更新语言按钮状态 */
                    active_language_btn(current_lan);
                    /** 写入文档标记*/
                    $("#doc_tag").html(current_tag);
                    /** 加载文档标题区*/
                    ajax_get_url('/' + current_tag + '/' + current_lan + '/catalog.php', 'doc_content');
                } else if (link_name === '/ajax/newNote.html') {
                    /** 修改标签内容*/
                    $('#newNote_tag').attr('value', global_new_note_tag);
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
                    case '/php/forum/note_new_summit.php':
                        /** 提交笔记成功会跳转到笔记页面*/
                        let url = '/php/forum/note_get.php?tag=' + res.tag + '&contentid=' + res.content_id;
                        ajax_get_url(url);
                        break;
                }
            }
        });
    }

    function active_rank_list_button(id) {
        $('#rank_1').removeClass("active");
        $('#rank_2').removeClass("active");
        $('#rank_3').removeClass("active");
        $('#rank_4').removeClass("active");
        $('#rank_5').removeClass("active");
        $('#rank_6').removeClass("active");
        $('#rank_7').removeClass("active");

        console.log('active_rank_list_button ' + id);
        let id_str = '#' + id;
        $(id_str).addClass("active");
    }

    function active_note_tag_button(btn_id) {
        $('#note_content_all').removeClass("active");
        /** jquery id名字中带有.的特殊处理*/
        $('[id="note_python3.7.2"]').removeClass("active");
        $('#note_技术讨论').removeClass("active");
        $('#note_灌水乐园').removeClass("active");
        console.log('active_note_tag_button ' + btn_id);
        $('[id="' + btn_id + '"]').addClass("active");
    }

    function active_language_btn(lan) {
        $('#doc_zh_cn').removeClass("active");
        $('#doc_en').removeClass("active");

        console.log('active_language_btn ' + lan);
        let lan_str = '#doc_' + lan;
        $(lan_str).addClass("active");
    }
</script>
</body>
</html>