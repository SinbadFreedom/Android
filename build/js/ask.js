/** 笔记模板数据，对应/hbs/ask_list.hbs*/
const hbs_ask_list = '<table class="table">\n' +
    '    <tbody>\n' +
    '    {{#ask}}\n' +
    '        <tr>\n' +
    '            <td class="text-center" width="30px" style="vertical-align: middle; font-weight: initial; font-size: 14px">\n' +
    '                <b>{{commentcount}}</b>\n' +
    '            </td>\n' +
    '            <td style="font-size: 18px">\n' +
    '                <span>[{{tag}}]</span>\n' +
    '                <a href="{{url_content}}">{{title}}</a>\n' +
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
const hbs_ask_info = '<table class="table">\n' +
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
    '</table>';

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
    ask_new_tag = e.target.innerText;
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
    /** 事件初始化*/
    $('#newAsk_commit').off('click', newAskCommit);
    $('#newAsk_commit').on('click', newAskCommit);
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
    let url_content = $(e.target).attr('href');
    console.log('askInfoLinkClick ' + url_content);
    ajax_get(url_content, askInfoLoadSuccess);
}

/** 问答详细信息加载成功*/
function askInfoLoadSuccess(res) {
    console.log('askInfoLoadSuccess ' + res);
    let data = JSON.parse(res);
    /** 时间戳转化为日期格式*/
    convertTimeStampToData(data.info);

    let html = Mustache.render(hbs_ask_info, data.info);
    /** 替换登录页面框架*/
    refreshContentArea(html);
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
    $('#ask_技术讨论').removeClass("active");
    $('#ask_灌水乐园').removeClass("active");

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
        // /** 时间戳转化成毫秒计算日期*/
        // info_arr[info].createtime = formatDate(info_arr[info].createtime * 1000);
        // if (info_arr[info].edittime) {
        //     /** 有回复的问答，才会有编辑信息*/
        //     info_arr[info].edittime = formatDate(info_arr[info].edittime * 1000);
        // }
    }

    let html = Mustache.render(hbs_ask_list, data);
    // console.log('askListLoadSuccess html ' + html);
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
