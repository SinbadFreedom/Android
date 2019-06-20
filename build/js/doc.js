/** 当前笔记页数*/
let note_page = 0;

let catalog_content = null;

function docSuccess(res) {
    console.log('docSuccess');
    /** 更新doc内容区*/
    refreshContentArea(res);

    /** 判断登录状态*/
    if (global_login_state) {
        /** 已经登录显示 提交按钮，没有登录显示默认按钮*/
        let html = '<button id="doc_reply_commit" class="btn btn-primary">提交</button>';
        $('#doc_login_state').html(html);
    }

    /** 移除事件侦听*/
    $('#doc_lan').off('click', 'button', docClickBtnLan);
    $('#doc_reply_commit').off('click', docClickBtnSummitReply);
    $('#doc_reply_login').off('click', docClickBtnLogin);
    $('#doc_catalog').off('click', 'a', docClickCatalogA);
    $('#doc_catalog').off('click', 'button', docClickCatalogBtn);
    $('#doc_btn_catalog').off('click', docBtnCatalog);
    $('#doc_last').off('click', docBtnGoLast);
    $('#doc_next').off('click', docBtnGoNext);

    /** 加入事件侦听*/
    $('#doc_lan').on('click', 'button', docClickBtnLan);
    $('#doc_reply_commit').on('click', docClickBtnSummitReply);
    $('#doc_reply_login').on('click', docClickBtnLogin);
    $('#doc_catalog').on('click', 'a', docClickCatalogA);
    $('#doc_catalog').on('click', 'button', docClickCatalogBtn);
    $('#doc_btn_catalog').on('click', docBtnCatalog);
    $('#doc_last').on('click', docBtnGoLast);
    $('#doc_next').on('click', docBtnGoNext);


    /** 更新tag按钮值*/
    $('#doc_tag').text(global_tag);
    /** 更新目录和内容*/
    resetCatalogAndDoc();
}

/** 按钮点击事件处理*/
function docClickBtnLan(e) {
    let btn_id = $(e.target).attr("id");
    console.log('docClickBtnLan btn_id ' + btn_id);
    if (btn_id === 'doc_zh_cn') {
        /** 中文*/
        global_lan = 'zh_cn';
    } else if (btn_id === 'doc_en') {
        /** 英文*/
        global_lan = 'en';
    }

    resetCatalogAndDoc();
}

/** 更新目录和文档区，用于初始化和语言切换*/
function resetCatalogAndDoc() {
    console.log('resetCatalogAndDoc');
    /** 加载文档标题区*/
    let url_catalog = '/doc/' + global_tag + '/' + global_lan + '/catalog.json';
    /** 语言按钮状态*/
    active_language_btn(global_lan);
    /** 加载目录区内容*/
    ajax_get(url_catalog, docLoadCatalogSuccess);
    /** 加载doc 笔记*/
    updateDocAndNote(global_page, global_anchor);
}

/** 加载目录完成回调方法*/
function docLoadCatalogSuccess(res) {
    catalog_content = res;
    /** 转化模板数据*/
    let html = Mustache.render(hbs_catalog, catalog_content);
    $('#doc_catalog').html(html);
    /** 更新目录区*/
    updateCatalogTitle(global_page, global_anchor);
}

/** 加载doc 笔记*/
function updateDocAndNote(doc_id, doc_anchor) {
    if (!doc_anchor) {
        /** 兼容文档中的 上/下一页按钮点击事件*/
        doc_anchor = doc_id + '_';
    }
    /** 更新目录区*/
    updateCatalogTitle(doc_id, doc_anchor);

    /** 加载content区内容*/
    let url_doc_content = '/doc/' + global_tag + '/' + global_lan + '/' + global_page + '.html';
    ajax_get(url_doc_content, docLoadContentSuccess);
    /** 清理笔记*/
    clearDocNote();
    /** 加载笔记*/
    getDocNote(0);
}

/** 加载文档完成回调方法*/
function docLoadContentSuccess(res) {
    /** 更新文档内容*/
    let display = $('#doc_content_stroll_area').css('display');
    if (display === 'none') {
        /** 内容区域是小屏隐藏状态，则显示文章内容区，隐藏目录区*/
        $('#doc_content_stroll_area').removeClass('d-none');
        $('#doc_content_stroll_area').removeClass('d-sm-none');
        $('#doc_catalog').addClass('d-none');
        $('#doc_catalog').addClass('d-sm-none');
    }

    $('#doc_content').html(res);
    /** 更新滚动条位置*/
    console.log('docLoadContentSuccess global_page ' + global_page + " global_anchor " + global_anchor);
    if (global_anchor) {
        /**
         * 滚动条至锚点
         * offsetTop是dom对象的属性，
         * jquery获取dom对象的方法是，获取数组的第一个元素$('#ID')[0]
         */
        let dom = $('#' + global_anchor)[0];
        if (dom) {
            let offsetTop = dom.offsetTop;
            /** 锚点位置减去网站导航栏距离*/
            $("#doc_content_stroll_area").scrollTop(offsetTop);
        }
    } else {
        /** 滚动条置顶*/
        $("#doc_content_stroll_area").scrollTop(0);
    }

    /** 更新上/下一篇按钮*/
    updateBtnLastAndNext();
}

/** 清空笔记*/
function clearDocNote() {
    note_page = 0;
    $('#doc_note').html('');
}

/** 更新笔记*/
function getDocNote(page_num) {
    note_page = page_num;
    console.log('getDocNote note_page ' + note_page);

    let url_note = '/php/forum/note_get.php?type=note&tag=' + global_tag + '&language=' + global_lan + '&contentid=' + global_page + '&page=' + page_num;
    ajax_get(url_note, docLoadNoteSuccess);
}

/** 加载doc 笔记 完成回调方法*/
function docLoadNoteSuccess(res) {
    let data = JSON.parse(res);
    /** 组合模板文件和数据文件，生成html*/
    let html = Mustache.render(hbs_note, data);
    /** 采用append添加，而不是采用html()方法直接替换*/
    $('#doc_note').append(html);
    /** 移除事件侦听*/
    $('#doc_note').off('click', 'button', clickBtnNotePage);
    /** 加入事件侦听*/
    $('#doc_note').on('click', 'button', clickBtnNotePage);
}

/** 点击note翻页事件*/
function clickBtnNotePage(e) {
    let btn_id = $(e.target).attr("id");
    console.log('clickBtnNotePage btn_id ' + btn_id);
    if (btn_id === 'note_page_next') {
        /** 下一页*/
        getDocNotePageNext();
    }
}

/** 下一页评论*/
function getDocNotePageNext() {
    note_page++;
    console.log('getDocNotePageNext note_page ' + note_page);
    getDocNote(note_page);
}

function active_language_btn(lan) {
    $('#doc_zh_cn').removeClass("active");
    $('#doc_en').removeClass("active");

    console.log('active_language_btn ' + lan);
    let lan_str = '#doc_' + lan;
    $(lan_str).addClass("active");
}

/** 点击登陆后科技条按钮*/
function docClickBtnLogin() {
    /** 显示登录页*/
    showLoginPage();
}

/** 回复文档点击事件*/
function docClickBtnSummitReply() {
    let reply = $('#doc_reply_text').val();
    /** 这里是提交表单前的非空校验*/
    if (reply === "" || !reply) {
        alert("请输入回复内容");
        /** 阻止表单提交*/
        return false;
    }

    /** 清空回复区*/
    $('#doc_reply_text').val('');

    let url_note = '/php/forum/note_reply.php';

    let data = {};
    data.type = 'note';
    data.tag = global_tag;
    data.language = global_lan;
    data.content_id = global_page;
    data.reply = reply;
    ajax_post(url_note, data, replyPostSuccessCallback);
}

/** 回复返回数据*/
function replyPostSuccessCallback(res) {
    console.log('replyPostSuccessCallback ' + res);
    let res_obj = JSON.parse(res);
    if (res_obj.state === 0) {
        /** 清空笔记*/
        clearDocNote();
        /** 回复成功, 更新笔记*/
        getDocNote(0);
    }
}

/** 点击目录区域的链接事件处理*/
function docClickCatalogA(e) {
    e.preventDefault();
    let doc_id = $(e.target).attr("catalog_doc_id");
    let doc_anchor = $(e.target).attr("catalog_anchor");

    console.log('docClickCatalogA global_page ' + global_page + ' global_anchor ' + global_anchor);

    updateDocAndNote(doc_id, doc_anchor);
}

/** 点击目录区的按钮事件： 展开/收起 目录标题*/
function docClickCatalogBtn(e) {
    let doc_id = $(e.target).attr("catalog_title_id");
    console.log('docClickCatalogBtn doc_id ' + doc_id);
    /** +/- 符号切换*/
    let text = $(e.target).text();
    console.log('docClickCatalogBtn text ' + text);
    if (text === '+') {
        $(e.target).text('-');
        /** 展开子目录*/
        $('#catalog_sub_' + doc_id).css('display', 'block');
    } else if (text === '-') {
        $(e.target).text('+');
        /** 收起子目录*/
        $('#catalog_sub_' + doc_id).css('display', 'none');
    }
}

/**
 * 更新目录active状态， 切换 +/- 符号，展开子标题
 * 点击目录标题，上/下 页时使用
 * 更新全局变量，global_page global_anchor
 */
function updateCatalogTitle(doc_id, doc_anchor) {
    /** 计算上一次显示active的标题id和当前要显示active的id*/
    console.log('updateCatalogTitle doc_id ' + doc_id + ' doc_anchor ' + doc_anchor);
    /** 上个标题状态移除active*/
    $('[catalog_anchor="' + global_anchor + '"]').removeClass('active');
    /** 标题状态切换为active*/
    $('[catalog_anchor="' + doc_anchor + '"]').addClass('active');

    /** 合并上次展开的目录*/
    $('#catalog_sub_' + global_page).css('display', 'none');
    /** +/- 符号切换 */
    $('[catalog_title_id="' + global_page + '"]').text("+");
    /** 展开子目录*/
    $('#catalog_sub_' + doc_id).css('display', 'block');
    /** +/- 符号切换 */
    $('[catalog_title_id="' + doc_id + '"]').text("-");

    /** 更新全局变量 global_page, global_anchor*/
    global_page = doc_id;
    global_anchor = doc_anchor;
}

/** 小屏显示的目录按钮点击事件*/
function docBtnCatalog(e) {
    console.log('docBtnCatalog ');
    /** 与 docLoadContentSuccess 方法对应*/
    /** 内容区域是小屏隐藏状态，点击目录按钮，则隐藏文章内容区，显示目录区*/
    $('#doc_content_stroll_area').addClass('d-none');
    $('#doc_content_stroll_area').addClass('d-sm-none');
    $('#doc_catalog').removeClass('d-none');
    $('#doc_catalog').removeClass('d-sm-none');
}

/** 上一篇*/
function docBtnGoLast() {
    if (global_page > 1) {
        /** 有上一页*/
        global_page -= 1;
        console.log('docBtnGoLast block global_page ' + global_page);
        let doc_anchor = global_page + '_';
        updateDocAndNote(global_page, doc_anchor);
    } else {
        /** 无上一页*/
        console.log('docBtnGoLast none global_page ' + global_page);
    }
}

/** 下一篇*/
function docBtnGoNext() {
    for (let i = 0; i < catalog_content.catalog.length; i++) {
        if (catalog_content.catalog[i].id === (global_page + 1)) {
            global_page += 1;
            console.log('docBtnGoNext block global_page ' + global_page);
            let doc_anchor = global_page + '_';
            updateDocAndNote(global_page, doc_anchor);
            break;
        }
    }

    /** 无下一页*/
    console.log('docBtnGoNext none global_page ' + global_page);
}

/** 更新上/下一篇按钮*/
function updateBtnLastAndNext() {
    if (global_page > 1) {
        console.log('docBtnGoLast block global_page ' + global_page);
        /** 有上一页*/
        $('#doc_last').css('display', 'inline');
    } else {
        console.log('docBtnGoLast none global_page ' + global_page);
        /** 无上一页*/
        $('#doc_last').css('display', 'none');
    }


    let contain_next = false;
    for (let i = 0; i < catalog_content.catalog.length; i++) {
        if (catalog_content.catalog[i].id === global_page + 1) {
            contain_next = true;
            break;
        }
    }

    if (contain_next) {
        console.log('docBtnGoNext block global_page ' + global_page);
        /** 有下一页*/
        $('#doc_next').css('display', 'inline');
    } else {
        console.log('docBtnGoNext none global_page ' + global_page);
        /** 无下一页*/
        $('#doc_next').css('display', 'none');
    }

}