let ask_new_tag = '';

/** 问答列表数据加载成功*/
function askLoadSuccess(res) {
    console.log('askListLoadSuccess');
    /** 替换登录页面框架*/
    refreshContentArea(res);
    /** 事件初始化*/
    $('#ask_new').off('click', 'button', askNew);
    $('#ask_tag').off('click', 'button', askTagClickBtn);
    $('#ask_new').on('click', 'button', askNew);
    $('#ask_tag').on('click', 'button', askTagClickBtn);
    /** 加载全部内容*/
    getAskList('ask_content_all');
}

/** 提问*/
function askNew(e) {
    if (!global_login_state) {
        alert('请先登录');
        return;
    }

    ask_new_tag = e.target.innerText;
    ask_new_tag = ask_new_tag.toLowerCase();
    console.log('askNew type ' + ask_new_tag);

    let url = '/ajax/newAsk.html';
    ajax_get(url, loadNewAskSuccess);
}

/** 新建问答页面加载成功*/
function loadNewAskSuccess(res) {
    console.log('loadNewAskSuccess');
    /** 替换登录页面框架*/
    refreshContentArea(res);
    /** 更新新建问答标签*/
    $('#newAsk_tag').text(ask_new_tag);

    /** 判断登陆状态*/
    if (global_login_state) {
        /** 已经登录显示 提交按钮，没有登录显示默认按钮*/
        let html = '<button class="btn btn-primary" id="newAsk_commit">提交</button>';
        $('#newAsk_login_state').html(html);
    }

    /** 事件初始化*/
    $('#newAsk_login').off('click', newAskLogin);
    $('#newAsk_commit').off('click', newAskCommit);
    $('#newAsk_login').on('click', newAskLogin);
    $('#newAsk_commit').on('click', newAskCommit);
}

function newAskLogin() {
    /** 显示登陆页面*/
    showLoginPage();
}

/** 提交问答*/
function newAskCommit(e) {
    let title = $("#newAsk_title").val();
    let content = $("#newAsk_content").val();
    /** 这里是提交表单前的非空校验*/
    if (ask_new_tag === "" || !ask_new_tag) {
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
    data.tag = ask_new_tag;
    data.title = title;
    data.content = content;
    ajax_post('/php/forum/ask_new_summit.php', data, askCommitSuccessCallback);
}

/** 问答提交成功回调*/
function askCommitSuccessCallback(res) {
    /**
     $res->state = 0;
     $res->tag = $tag;
     $res->content_id = $content_id;
     */
    let obj = JSON.parse(res);
    let tag = obj.tag;
    /** 返回问答列表*/
    let url = '/ajax/ask.html';
    ajax_get(url, askLoadSuccess);
}

function askTagClickBtn(e) {
    let btn_id = $(e.target).attr('id');
    console.log('askTagClickBtn ' + btn_id);

    getAskList(btn_id);
}

/** 点击问答链接, 获取链接内容*/
function askInfoLinkClick(e) {
    e.preventDefault();
    let ask_tag = $(e.target).attr('ask_tag');
    let ask_content_id = $(e.target).attr('ask_content_id');
    getAskInfo(ask_tag, ask_content_id);
}

/** 获取问答的详细内容*/
function getAskInfo(tag, content_id) {
    /** 由a标签参数组成url*/
    let url_content = '/php/forum/ask_info.php?tag=' + tag + '&language=zh_cn&contentid=' + content_id;
    console.log('getAskInfo tag ' + tag + ' content_id ' + content_id);
    ajax_get(url_content, askInfoLoadSuccess);
}

/** 问答详细信息加载成功*/
function askInfoLoadSuccess(res) {
    if (!res) {
        console.error('askInfoLoadSuccess res null');
        return;
    }

    console.dir(res);
    console.log('askInfoLoadSuccess ' + res);
    let data = JSON.parse(res);
    /** 时间戳转化为日期格式*/
    convertTimeStampToData(data.info);

    let html = Mustache.render(hbs_ask_info, data.info);
    /** 替换登录页面框架*/
    refreshContentArea(html);

    /** 判断登录状态*/
    if (global_login_state) {
        /** 已经登录显示 提交按钮，没有登录显示默认按钮*/
        let html = '<button id="ask_info_btn_reply" class="btn btn-primary">提交</button>';
        $('#ask_info_login_state').html(html);
    }

    /** 回复按钮点击事件*/
    $('#ask_info_btn_login').off('click', askRelyLogin);
    $('#ask_info_btn_reply').off('click', askReplyClick);

    $('#ask_info_btn_login').on('click', askRelyLogin);
    $('#ask_info_btn_reply').on('click', askReplyClick);

    getAskReply(data.info.tag, 'zh_cn', data.info.contentid, 0);
}

function askRelyLogin() {
    /** 显示登陆页面*/
    showLoginPage();
}

/** 更新问答回复*/
function getAskReply(tag, lan, content_id, page_num) {
    console.log('getAskReply page_num ' + page_num + ' lan ' + lan + ' content_id ' + content_id);
    let url_ask_reply = '/php/forum/note_get.php?type=reply&tag=' + tag + '&language=' + lan + '&contentid=' + content_id + '&page=' + page_num;
    ajax_get(url_ask_reply, askReplyLoadSuccess);
}

/** 问答回复加载成功*/
function askReplyLoadSuccess(res) {
    console.log('askReplyLoadSuccess res ' + res);
    /** hbs 和data 组合html*/
    let data = JSON.parse(res);
    let html = Mustache.render(hbs_note, data);
    $("#ask_info_reply_area").html(html);
}

/** 点击回复按钮*/
function askReplyClick(e) {
    console.log('askReplyClick');

    let reply = $("#ask_info_txt_reply").val();
    if (reply === "" || !reply) {
        alert("输入回复内容");
        /** 阻止表单提交*/
        return false;
    }

    let data = {};
    data.type = 'reply';
    data.tag = $("#ask_info_tag").text();
    data.language = 'zh_cn';
    data.content_id = $("#ask_info_content_id").text();
    data.reply = reply;

    ajax_post('/php/forum/note_reply.php', data, replyCommitSuccessCallback);
}

/** 回复提交成功*/
function replyCommitSuccessCallback(res) {
    console.log('replyCommitSuccessCallback ' + res);
    console.dir(res);
    let res_obj = JSON.parse(res);
    if (res_obj.state === 0) {
        /** 回复成功, 更新问答内容*/
        getAskInfo(res_obj.tag, res_obj.content_id);
    }
}

/** 获取问答列表*/
function getAskList(btn_id) {
    console.log('getAskList btn_id ' + btn_id);
    active_ask_tag_button(btn_id);

    let c_index = btn_id.indexOf("_");
    let param = btn_id.slice(c_index + 1);

    /** 加载对应标签的问题列表*/
    let url = '/php/forum/ask_list.php?tag=' + param + "&language=zh_cn";
    ajax_get(url, askListLoadSuccess);
}

function active_ask_tag_button(btn_id) {
    console.log('active_ask_tag_button ' + btn_id);
    $('#ask_content_all').removeClass("active");

    /** jquery id名字中带有"."的特殊处理*/
    $('[id="ask_python"]').removeClass("active");
    $('#ask_java').removeClass("active");
    $('#ask_综合').removeClass("active");

    $('[id="' + btn_id + '"]').addClass("active");
}

/** ask列表内容加载完成*/
function askListLoadSuccess(res) {
    /** hbs 和data 组合html*/
    let data = JSON.parse(res);
    /** 组合模板文件和数据文件，生成html*/
    let info_arr = data.ask;

    /** 时间戳转化为日期格式*/
    for (let info in info_arr) {
        console.dir(info_arr[info]);
        convertTimeStampToData(info_arr[info])
    }

    let html = Mustache.render(hbs_ask_list, data);
    /** 显示ask列表*/
    $('#ask_tag_info').html(html);
    /** a 标签点击事件*/
    $('#ask_tag_info').off('click', 'a', askInfoLinkClick);
    $('#ask_tag_info').on('click', 'a', askInfoLinkClick);
}

/** 时间戳转化成毫秒计算日期*/
function convertTimeStampToData(info) {
    info.createtime = formatDate(info.createtime * 1000);
    if (info.edittime) {
        /** 有回复的问答，才会有编辑信息*/
        info.edittime = formatDate(info.edittime * 1000);
    }
}

/** 时间戳转化为日期格式*/
function formatDate(date) {
    let d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;
    // return [year, month, day].join('-');
    return [month, day].join('-');
}
/** 当前笔记页数*/
let note_page = 0;

let catalog_content = null;
let doc_anchor_pos_arr = null;

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
    $('#doc_lan').off('click', docClickBtnLan);
    $('#doc_reply_commit').off('click', docClickBtnSummitReply);
    $('#doc_reply_login').off('click', docClickBtnLogin);
    $('#doc_catalog').off('click', 'a', docClickCatalogA);
    $('#doc_catalog').off('click', 'button', docClickCatalogBtn);
    $('#doc_search_res').off('click', 'span', docClickSearchResSpan);
    $('#doc_btn_catalog').off('click', docBtnCatalog);
    $('#doc_last').off('click', docBtnGoLast);
    $('#doc_next').off('click', docBtnGoNext);
    $('#doc_search').off('input propertychange', docBtnSearchOnChange);

    /** 加入事件侦听*/
    $('#doc_lan').on('click', docClickBtnLan);
    $('#doc_reply_commit').on('click', docClickBtnSummitReply);
    $('#doc_reply_login').on('click', docClickBtnLogin);
    $('#doc_catalog').on('click', 'a', docClickCatalogA);
    $('#doc_catalog').on('click', 'button', docClickCatalogBtn);
    $('#doc_search_res').on('click', 'span', docClickSearchResSpan);
    $('#doc_btn_catalog').on('click', docBtnCatalog);
    $('#doc_last').on('click', docBtnGoLast);
    $('#doc_next').on('click', docBtnGoNext);
    $('#doc_search').on('input propertychange', docBtnSearchOnChange);

    $('#doc_content_stroll_area').scroll(docStrollEvent);

    /** 更新tag按钮值*/
    $('#doc_tag').text(global_tag);
    /** 更新目录和内容*/
    resetCatalogAndDoc();
}

/** 按钮点击事件处理*/
function docClickBtnLan(e) {
    let text = $(e.target).text();
    if (text === '简') {
        /** 中文*/
        global_lan = 'zh_cn';
        $(e.target).text('En');
    } else if (text === 'En') {
        /** 英文*/
        global_lan = 'en';
        $(e.target).text('简');
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
    /** 整体目录*/
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
    $('#doc_content').html(res);

    /** 内容区域是小屏隐藏状态，则显示文章内容区，隐藏目录区*/
    $('#doc_content_stroll_area').css('z-index', 99);
    $('#doc_catalog').css('z-index', 90);

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

    /** google pretty 代码高亮*/
    PR.prettyPrint();

    /** 更新本文目录数据对象*/
    updateDocAnchorInfo();
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
    doc_id = parseInt(doc_id);
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
    // $('#doc_content_stroll_area').addClass('d-none');
    // $('#doc_content_stroll_area').addClass('d-sm-none');
    // $('#doc_catalog').removeClass('d-none');
    // $('#doc_catalog').removeClass('d-sm-none');

    $('#doc_content_stroll_area').css('z-index', 90);
    $('#doc_catalog').css('z-index', 99);
}

/** 检索返回数据*/
function searchSuccess(res) {
    console.log('searchSuccess ' + res);
    let res_obj = JSON.parse(res);

    let res_str = '';
    res_obj.data.forEach(function (element) {
        console.log('element ' + element);
        let str = '<span class="list-group-item">' + element + '</span>';
        res_str += str;
    });

    $('#doc_search_res').html(res_str);
    $('#doc_search_res').css('display', 'block');
}

/** 检索框输入事件*/
function docBtnSearchOnChange(e) {
    let val = $(e.target).val();
    if (val.length > 0) {
        /** 转小写*/
        val = val.toLowerCase();
        /** 移除两端空格*/
        val.trim();
        console.log('docBtnSearchOnChange val ' + val);
        /** 检索输入信息*/
        let type = global_tag + '_' + global_lan;
        let url = '/php/search/search.php?type=' + type + '&keys=' + val;
        ajax_get(url, searchSuccess);
    } else {
        /** 清空搜索框，隐藏内容*/
        clearSearchInput();
    }
}

/** 清空搜索框，隐藏内容*/
function clearSearchInput() {
    $('#doc_search_res').html('');
    $('#doc_search').val('');
}

/** 点击搜索结果区*/
function docClickSearchResSpan(e) {
    e.preventDefault();
    let text = $(e.target).text();
    let title = text.split(' ')[0];
    let doc_id = parseInt(text.split('.')[0]);
    let doc_anchor = title.replace(/\./g, '_');
    /** 清空搜索结果*/
    clearSearchInput();
    /** 跳转到指定文档*/
    console.log('docClickSearchResSpan doc_id ' + doc_id + ' doc_anchor ' + doc_anchor);
    updateDocAndNote(doc_id, doc_anchor);

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

/** 获取本文的锚点信息，目录中的对应的本文的锚点名称，及文档中锚点对应的坐标*/
function updateDocAnchorInfo() {
    doc_anchor_pos_arr = [];

    for (let i = 0; i < catalog_content.catalog.length; i++) {
        if (catalog_content.catalog[i].id === global_page) {
            let title_main = catalog_content.catalog[i];

            /** 本文章所有锚点名称，包括主标题，一级，二级子标题*/
            /** 主标题anchor*/
            let dom_title = $('#' + title_main.anchor)[0];
            let title_obj = {};
            title_obj.anchor_name = title_main.anchor;
            title_obj.anchor_off_set_top = dom_title.offsetTop;
            doc_anchor_pos_arr.push(title_obj);

            if (title_main.sub_1) {
                /** 一级子标题anchor*/
                for (let j in title_main.sub_1) {
                    let sub_1 = title_main.sub_1[j];

                    let dom_sub_1 = $('#' + sub_1.anchor)[0];
                    let sub_1_obj = {};
                    sub_1_obj.anchor_name = sub_1.anchor;
                    sub_1_obj.anchor_off_set_top = dom_sub_1.offsetTop;
                    doc_anchor_pos_arr.push(sub_1_obj);

                    if (sub_1.sub_2) {
                        /** 二级子标题anchor*/
                        for (let k in sub_1.sub_2) {
                            let sub_2 = sub_1.sub_2[k];

                            let dom_sub_2 = $('#' + sub_2.anchor)[0];
                            let sub_2_obj = {};
                            sub_2_obj.anchor_name = sub_2.anchor;
                            sub_2_obj.anchor_off_set_top = dom_sub_2.offsetTop;
                            doc_anchor_pos_arr.push(sub_2_obj);
                        }
                    }
                }
            }
            break;
        }
    }
}

/** 滚动条上一次的偏移量，用来判断是上划还是下划*/
let last_stroll_pos = 0;

/** 文档区滚动条滚动事件*/
function docStrollEvent(e) {
    if (doc_anchor_pos_arr) {
        /** 根据本文目录对象的锚点数据，更新目录active 的锚点*/
        let stroll_top = $("#doc_content_stroll_area").scrollTop();
        /** 判断当前锚点位置与 滚动条strollTop的关系， 如果超过下一个锚点位置，则更新当前锚点*/

        /** 是否是下划*/
        let is_down;

        if (last_stroll_pos < stroll_top) {
            /** 下划*/
            is_down = true;
        } else if (last_stroll_pos > stroll_top) {
            /** 上划*/
            is_down = false;
        }

        for (let i = 0; i < doc_anchor_pos_arr.length; i++) {
            if (doc_anchor_pos_arr[i].anchor_name === global_anchor) {
                if (is_down) {
                    /** 下划，计算下一个锚点*/
                    if (i < doc_anchor_pos_arr.length - 1) {
                        let next_off_set = doc_anchor_pos_arr[i + 1].anchor_off_set_top;
                        if (stroll_top >= next_off_set) {
                            /** 更新目录区下一个锚点*/
                            updateCatalogTitle(global_page, doc_anchor_pos_arr[i + 1].anchor_name);
                        }
                    }
                } else {
                    /** 上划，计算上一个*/
                    if (i > 0) {
                        let last_off_set = doc_anchor_pos_arr[i - 1].anchor_off_set_top;
                        if (stroll_top <= last_off_set) {
                            /** 更新目录区下一个锚点*/
                            updateCatalogTitle(global_page, doc_anchor_pos_arr[i - 1].anchor_name);
                        }
                    }
                }

                break;
            }
        }

        /** 更新坐标偏移*/
        last_stroll_pos = stroll_top;
    }
}/** 笔记模板数据，对应/hbs/ask_list.hbs*/
const hbs_ask_list = '<table class="table">\n' +
    '    <tbody>\n' +
    '    {{#ask}}\n' +
    '        <tr>\n' +
    '            <td class="text-center" width="30px" style="vertical-align: middle; font-weight: initial; font-size: 14px">\n' +
    '                <b>{{comment_count}}</b>\n' +
    '            </td>\n' +
    '            <td style="font-size: 18px">\n' +
    '                <a ask_tag="{{tag}}" ask_content_id="{{contentid}}" href="{{url_content}}">{{title}}</a>\n' +
    '                <div class="d-lg-none" style="font-size: 14px">\n' +
    '                    <div>\n' +
    '                        <span>{{authorname}}</span><span>{{createtime}}</span><span>{{editorname}}</span>{{edittime}}\n' +
    '                    </div>\n' +
    '                </div>\n' +
    '            </td>\n' +
    '            <td class="d-none d-lg-block text-right">\n' +
    '                <span>{{authorname}}</span><span>{{createtime}}</span><span>{{editorname}}</span>{{edittime}}\n' +
    '            </td>\n' +
    '        </tr>\n' +
    '    {{/ask}}\n' +
    '    </tbody>\n' +
    '</table>';

/** 笔记模板数据，对应/hbs/ask_info.hbs*/
const hbs_ask_info = '<div class="row">\n' +
    '    <button type="button" id="ask_info_tag" class="btn btn-light active">{{tag}}</button>\n' +
    '</div>\n' +
    '\n' +
    '<div style="display: none">\n' +
    '    <span id="ask_info_content_id">{{contentid}}</span>\n' +
    '</div>\n' +
    '\n' +
    '<table class="table">\n' +
    '    <tbody>\n' +
    '    <tr>\n' +
    '        <td width="96px">\n' +
    '            <img src="{{author_figure}}" width="48px" height="48px">\n' +
    '            <div class="text-center">\n' +
    '                <span>{{authorname}}</span>\n' +
    '            </div>\n' +
    '        </td>\n' +
    '        <td width="100%" valign="top">\n' +
    '            <div class="row">\n' +
    '                <span class="ml-auto"><small>{{createtime}}</small></span>\n' +
    '            </div>\n' +
    '            <div class="row">\n' +
    '                <span>{{title}}</span>\n' +
    '            </div>\n' +
    '            <div>\n' +
    '                <span>{{content}}</span>\n' +
    '            </div>\n' +
    '        </td>\n' +
    '    </tr>\n' +
    '    </tbody>\n' +
    '</table>\n' +
    '\n' +
    '<div id="ask_info_reply_area">\n' +
    '问答回复区\n' +
    '</div>\n' +
    '\n' +
    '<textarea class=form-control id="ask_info_txt_reply" rows="5" placeholder="输入内容"></textarea>\n' +
    '\n' +
    '<div id="ask_info_login_state">\n' +
    '    <button class="btn btn-warning" id="ask_info_btn_login">登陆后可提交</button>\n' +
    '<!--    <button id="ask_info_btn_reply" class="btn btn-primary">回复</button>-->\n' +
    '</div>';

/** 笔记模板数据，对应/hbs/note.hbs*/
const hbs_note = '<table>\n' +
    '    <tbody>\n' +
    '    {{#notes}}\n' +
    '        <tr>\n' +
    '            <td width="96px">\n' +
    '                <img src="{{editor_figure}}" width="48px" height="48px">\n' +
    '                <div class="text-center">\n' +
    '                    <span>{{editor_name}}</span>\n' +
    '                </div>\n' +
    '            </td>\n' +
    '            <td width="100%" valign="top">\n' +
    '                <div class="row">\n' +
    '                    <span class="ml-auto"><small>{{edit_time}}</small></span>\n' +
    '                </div>\n' +
    '                <div>\n' +
    '                    <span>{{reply}}</span>\n' +
    '                </div>\n' +
    '            </td>\n' +
    '        </tr>\n' +
    '    {{/notes}}\n' +
    '    </tbody>\n' +
    '</table>\n' +
    '\n' +
    '<div class="btn-group">\n' +
    '    {{#page_next}}\n' +
    '        <button id="note_page_next" class="btn btn-light">&or;</button>\n' +
    '    {{/page_next}}\n' +
    '</div>\n';

/** 目录模板数据，对应/hbs/catalog.hbs*/
const hbs_catalog = '{{#catalog}}\n' +
    '    <div class="d-flex justify-content-start align-items-lg-start">\n' +
    '        <button catalog_title_id="{{id}}" class="btn">+</button>\n' +
    '        <a catalog_doc_id="{{id}}" catalog_anchor="{{anchor}}" href="{{url}}"\n' +
    '           class="list-group-item list-group-item-action">{{title}}</a>\n' +
    '    </div>\n' +
    '    <div id="catalog_sub_{{id}}" style="display: none">\n' +
    '        {{#sub_1}}\n' +
    '            <a catalog_doc_id={{id}} catalog_anchor="{{anchor}}" href="{{url}}"\n' +
    '               style="padding-left: 3em"\n' +
    '               class="list-group-item list-group-item-action">{{title}}</a>\n' +
    '            {{#sub_2}}\n' +
    '                <a catalog_doc_id={{id}} catalog_anchor="{{anchor}}" href="{{url}}"\n' +
    '                   style="padding-left: 4em"\n' +
    '                   class="list-group-item list-group-item-action">{{title}}</a>\n' +
    '            {{/sub_2}}\n' +
    '        {{/sub_1}}\n' +
    '    </div>\n' +
    '{{/catalog}}';/** 全局变量*/
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
        let login_state_btn = '<button type="button" id="nav_btn_figure" class="btn btn-primary ml-auto"><img id="nav_btn_figure"  class="rounded" src="' + res + '" width="24px" height="24px"></button>';
        $("#index_login_state").html(login_state_btn);
        /** 更新经验*/
        let url_exp = '/php/update_exp.php';
        ajax_get(url_exp);
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
    console.log("ajax_post: " + link_name + " data " + JSON.stringify(data));
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
    $('#index_disclaimer').off('click', initDisclaimer);
    $('#index_disclaimer').on('click', initDisclaimer);

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

/** 免责声明*/
function initDisclaimer() {
    let url_doc = '/ajax/disclaimer.html';
    /** disclaimer.html, 完成后执行回调*/
    ajax_get(url_doc, disclaimerSuccess);
}

function disclaimerSuccess(res) {
    console.log('disclaimerSuccess');
    /** 更新doc内容区*/
    refreshContentArea(res);
}function loginLoadSuccess(res) {
    console.log('loginLoadSuccess');
    /** 替换登录页面框架*/
    refreshContentArea(res);

    /** 事件初始化*/
    $('#login_qq').off('click', loginClickBtnQQ);
    $('#login_wx').off('click', loginClickBtnWX);

    $('#login_qq').on('click', loginClickBtnQQ);
    $('#login_wx').on('click', loginClickBtnWX);
}

/** qq登录*/
function loginClickBtnQQ() {
    window.location.href = "/lib/Connect2.1/example/oauth/index.php";
}

/** wx登录*/
function loginClickBtnWX() {
    var call_back_url = encodeURI('https://panda-doc.com/lib/wechat/login_wc_callback.php');
    window.location.href =
        'https://open.weixin.qq.com/connect/qrconnect?appid=wx7c369f8fe5340534&redirect_uri=' + call_back_url + '&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect';
}
function loadProfileSuccess(res) {
    console.log('loadProfileSuccess');
    refreshContentArea(res);

    let url_profile = '/php/profile.php';
    ajax_get(url_profile, loadProfileDataCallBack);
}

function loadProfileDataCallBack(res) {
    console.log('loadProfileDataCallBack ' + res);
    /**
     $res->state = 0;
     $res->name = $user_info->nickname;
     $res->exp = $user_info->exp;
     $res->create_time = date('Y-m-d', $user_info->create_time);
     $res->level = $user_info->level;
     $res->exp_max = $exp_max;
     $res->percent = $percent;
     */
    let res_obj = JSON.parse(res);
    let name = res_obj.name;
    let id = res_obj.id;
    let exp = res_obj.exp;
    let create_time = res_obj.create_time;
    let level = res_obj.level;
    let exp_max = res_obj.exp_max;
    let percent = res_obj.percent;

    console.log("name " + name + " id " + id);

    $('#profile_name').attr('value', name);
    $('#profile_id').attr('value', id);
    $('#profile_create_time').attr('value', create_time);
    $('#profile_level').attr('value', level);
    $('#profile_exp').attr('value', exp + '/' + exp_max);
    /** 经验条*/
    $('#profile_progress').css('width', percent + '%');
    $('#profile_progress').attr('aria-valuenow', exp);
    $('#profile_progress').attr('aria-valuemax', exp_max);
}/** 排行模板数据，对应/hbs/rank.hbs*/
const hbs_rank = '{{#info}}\n' +
    '    <tr>\n' +
    '        <th valign="middle">{{rank}}</th>\n' +
    '        <td><img class="img-responsive center-block" src="{{headimgurl}}" width="50px" height="50px"></td>\n' +
    '        <td valign="middle">{{nickname}}</td>\n' +
    '        <td valign="middle">{{level}}</td>\n' +
    '        <td valign="middle">{{exp}}</td>\n' +
    '    </tr>\n' +
    '{{/info}}';


function rankLoadSuccess(res) {
    console.log('rankLoadSuccess');
    /** 替换排行榜框架*/
    refreshContentArea(res);
    /** 事件初始化*/
    $('#rank_nav').off('click', 'button', rankClickBtn);
    $('#rank_nav').on('click', 'button', rankClickBtn);

    getPageRank('rank_1');

}

/** 排行榜导航按钮点击事件处理*/
function rankClickBtn(e) {
    let btn_id = $(e.target).attr("id");
    getPageRank(btn_id);
}

/** 排行榜按钮点击*/
function getPageRank(type) {
    /** 更新排行榜按钮状态*/
    active_rank_list_button(type);
    /** 更新排行榜提示*/
    updateRankTip(type);
    /** 加载排行榜数据*/
    let url = '/php/rank_list.php?type=' + type;
    ajax_get(url, rankInfoLoadSuccess);
}

/** 加载排行榜数据完成*/
function rankInfoLoadSuccess(res) {
    let data = JSON.parse(res);
    /** 组合模板文件和数据文件，生成html*/
    let html = Mustache.render(hbs_rank, data);
    /** 显示排行榜数据*/
    $('#rank_list_info').html(html);
}

/** 更新排行榜按钮状态*/
function active_rank_list_button(id) {
    $('#rank_1').removeClass('active');
    $('#rank_2').removeClass('active');
    $('#rank_3').removeClass('active');
    $('#rank_4').removeClass('active');
    $('#rank_5').removeClass('active');
    $('#rank_6').removeClass('active');
    $('#rank_7').removeClass('active');

    console.log('active_rank_list_button ' + id);
    let id_str = '#' + id;
    $(id_str).addClass('active');
}

let tip = '';

function updateRankTip(type) {
    switch (type) {
        case 'rank_1':
            tip = '今日排行榜 每分钟更新';
            break;
        case 'rank_2':
            tip = '昨日排行榜 每日0点更新';
            break;
        case 'rank_3':
            tip = '本周排行榜 每分钟更新';
            break;
        case 'rank_4':
            tip = '上周排行榜 每周一0点更新';
            break;
        case 'rank_5':
            tip = '本月排行榜 每分钟更新';
            break;
        case 'rank_6':
            tip = '上月排行榜 每月1日0点更新';
            break;
        case 'rank_7':
            tip = '总排行榜 每分钟更新';
            break;
    }

    /** 显示排行榜提示*/
    $('#rank_tip').html(tip);
}
