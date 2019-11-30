/** 全局变量*/
/** 当前文档tag*/
let global_tag = '';
/** 语言状态*/
let global_lan = 'zh_cn';
/** 当前文档显示的文章*/
let global_page = 1;
/** 当前文档显示的文章的锚点*/
let global_anchor = '1_';
/** 登陆状态*/
let global_login_state = false;

$(document).ready(function () {
    console.log('index document ready.');
    /** 事件初始化*/
    $('#nav_bar').off('click', 'button', navClickBtn);
    $('#nav_bar').on('click', 'button', navClickBtn);
    /** 加载index框架*/
    let url_index = '/ajax/index.html';
    ajax_get(url_index, indexLoadSuccess);

    // /** 获取头像信息，如果没有则显示登录按钮*/
    // let url_figure = '/php/figure_url.php';
    // ajax_get(url_figure, onGetFigureUrl);

    /**
     * 通过get参数，初始化页面
     * url 示例:
     * /index.html?tag=%E6%8A%80%E6%9C%AF%E8%AE%A8%E8%AE%BA&language=zh_cn&contentid=100051&nav=ask
     */
    let nav = getQueryString('nav');
    if (nav) {
        if (nav === 'ask') {
            /** 跳转至问答*/
            global_tag = getQueryString('tag');
            global_lan = getQueryString('language');

            let content_id = getQueryString('contentid');
            active_nav_button('nav_btn_ask');

            getAskInfo(global_tag, content_id);
        } else if (nav === 'doc') {
            /** 跳转至文档*/
            global_tag = getQueryString('tag');
            global_lan = getQueryString('language');
            global_page = getQueryString('contentid');
            global_page = parseInt(global_page);
            global_anchor = getQueryString('anchor');

            initDocPage();
        }
    }
});

function onGetFigureUrl(res) {
    console.log('onGetFigureUrl res ' + res);
    if (res) {
        global_login_state = true;
        /** 已经登陆，获取头像信息, 显示头像图标*/
        let login_state_btn = '<button type="button" id="nav_btn_figure" class="btn ml-auto"><img id="nav_btn_figure"  class="rounded" src="' + res + '" width="24px" height="24px"></button>';
        $("#index_login_state").html(login_state_btn);
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
            showLoginPage();
            break;
        case 'nav_btn_figure':
            url = '/ajax/profile.html';
            ajax_get(url, loadProfileSuccess);
            break;
    }
}

/** 显示登录页面*/
function showLoginPage() {
    let url = '/ajax/login.html';
    ajax_get(url, loginLoadSuccess);
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

/** 获取get参数*/
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return r[2];
    return null;
}

/** 加载index.html页面完成*/
function indexLoadSuccess(res) {
    console.log('indexLoadSuccess');
    /** 更新index内容区*/
    refreshContentArea(res);
    /** 事件初始化*/
    $('#index_tag').off('click', 'button', indexClickBtnTag);
    $('#index_tag').on('click', 'button', indexClickBtnTag);

    /** 获取头像信息，如果没有则显示登录按钮*/
    let url_figure = '/php/figure_url.php';
    ajax_get(url_figure, onGetFigureUrl);
}

/** 文章tag按钮点击*/
function indexClickBtnTag(e) {
    let btn_id = $(e.target).attr("id");
    console.log('indexClickBtnTag btn_id ' + btn_id);
    let c_index = btn_id.indexOf("_");
    /** 跳过‘_’*/
    let param = btn_id.slice(c_index + 1);
    /** 设置全局变量global_tag*/
    global_tag = param;

    initDocPage();
}

/** 加载doc框架*/
function initDocPage() {
    let url_doc = '/ajax/doc.html';
    /** 加载doc.html, 完成后执行回调*/
    ajax_get(url_doc, docSuccess);
}