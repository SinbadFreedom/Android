let hbs_note = null;
let note_page = 0;

function docSuccess(res) {
    console.log('docSuccess');
    /** 更新doc内容区*/
    refreshContentArea(res);
    /** 移除事件侦听*/
    $('#doc_lan').off('click', 'button', docClickBtnLan);
    $('#doc_reply_commit').off('click', docClickBtnSummitReply);
    $('#doc_catalog').off('click', 'a', docClickCatalogA);

    /** 加入事件侦听*/
    $('#doc_lan').on('click', 'button', docClickBtnLan);
    $('#doc_reply_commit').on('click', docClickBtnSummitReply);
    $('#doc_catalog').on('click', 'a', docClickCatalogA);

    /** 更新tag按钮值*/
    $('#doc_tag').text(global_tag);
    /** 更新目录和内容*/
    updateDocCatalogAndContent();
}

/** 按钮点击事件处理*/
function docClickBtnLan(e) {
    let btn_id = $(e.target).attr("id");
    console.log('docClickBtnLan btn_id ' + btn_id);
    switch (btn_id) {
        case 'doc_zh_cn':
            /** 中文*/
            global_lan = 'zh_cn';
            /** 更新文档语言*/
            updateDocCatalogAndContent();
            break;
        case 'doc_en':
            /** 英文*/
            global_lan = 'en';
            /** 更新文档语言*/
            updateDocCatalogAndContent();
            break;
    }
}

function updateDocCatalogAndContent() {
    /** 加载文档标题区*/
    let url_catalog = '/doc/' + global_tag + '/' + global_lan + '/catalog.php';
    /** 语言按钮状态*/
    active_language_btn(global_lan);
    /** 加载目录区内容*/
    ajax_get(url_catalog, docLoadCatalogSuccess);
    /** 加载doc 笔记*/
    updateDocContentAndNote();
}

/** 加载目录完成回调方法*/
function docLoadCatalogSuccess(res) {
    $('#doc_catalog').html(res);
}

/** 加载doc 笔记*/
function updateDocContentAndNote() {
    /** 滚动条置顶*/
    window.scrollTo(0, 0);
    /** 加载content区内容*/
    let url_doc_content = '/doc/' + global_tag + '/' + global_lan + '/' + global_page;
    ajax_get(url_doc_content, docLoadContentSuccess);
    /** 清理笔记*/
    clearDocNote();
    /** 加载笔记*/
    getDocNote(0);
}

/** 加载文档完成回调方法*/
function docLoadContentSuccess(res) {
    /** 更新文档内容*/
    $('#doc_content').html(res);
}

/** 清空笔记*/
function clearDocNote() {
    $('#doc_note').html('');
}

/** 更新笔记*/
function getDocNote(page_num) {
    note_page = page_num;
    if (hbs_note == null) {
        let url = '/ajax/note.hbs';
        hbs_note = ajax_get_sync(url).responseText;
    }

    console.log('getDocNote note_page ' + note_page);

    let file_number = global_page.split('.')[0];
    let url_note = '/php/note/note_get.php?tag=' + global_tag + '&language=' + global_lan + '&contentid=' + file_number + '&page=' + page_num;
    ajax_get(url_note, docLoadNoteSuccess);
}

/** 加载doc 笔记 完成回调方法*/
function docLoadNoteSuccess(res) {
    let data = JSON.parse(res);
    /** 组合模板文件和数据文件，生成html*/
    let html = Mustache.render(hbs_note, data);
    // $('#doc_note').html(html);
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

    let url_note = '/php/note/note_reply.php';

    let data = {};
    data.tag = global_tag;
    data.language = global_lan;
    data.content_id = global_page.split('.')[0];
    data.reply = reply;
    ajax_post(url_note, data, replyPostSuccessCallback);
}

/** 回复返回数据*/
function replyPostSuccessCallback(res) {
    console.log('replyPostSuccessCallback ' + res);
    let res_obj = JSON.parse(res);
    if (res_obj.state === 0) {
        /** 回复成功, 更新笔记*/
        getDocNote(0);
    }
}

/** 点击目录区域的链接事件处理*/
function docClickCatalogA(e) {
    e.preventDefault();
    let a_href = $(e.target).attr("href");
    console.log('docClickCatalogA ' + a_href);
    /** 更新全局变量 global_page*/
    global_page = a_href;
    updateDocContentAndNote();
}

/** 上/下一篇 按钮处理*/
function doc_go(doc_num) {
    global_page = doc_num + ".php";
    console.log('doc_go ' + global_page);

    updateDocContentAndNote();
}
