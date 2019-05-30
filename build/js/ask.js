function askLoadSuccess(res) {
    console.log('askLoadSuccess');
    /** 替换登录页面框架*/
    refreshContentArea(res);
    /** 事件初始化*/
    $('#ask_tag').off('click', 'button', askTagClickBtn);
    $('#ask_tag').on('click', 'button', askTagClickBtn);

    /** 加载全部内容*/
    getNoteList('ask_content_all');
}

function askTagClickBtn(e) {
    let btn_id = $(e.target).attr("id");
    console.log('askTagClickBtn ' + btn_id);

    getNoteList(btn_id);
}

function getNoteList(btn_id) {
    console.log('getNoteList btn_id ' + btn_id);
    active_ask_tag_button(btn_id);

    let c_index = btn_id.indexOf("_");
    let param = btn_id.slice(c_index + 1);

    /** 加载对应标签的问题列表*/
    let url = '/php/forum/index.php?tag=' + param;
    ajax_get(url, askLoadInfoSuccess);
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
function askLoadInfoSuccess(res) {
    $('#ask_tag_info').html(res);
}
