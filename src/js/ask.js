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
