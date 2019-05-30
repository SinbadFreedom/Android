/** 加载index.html页面完成*/
function indexLoadSuccess(res) {
    console.log('indexLoadSuccess');
    /** 更新index内容区*/
    refreshContentArea(res);
    /** 事件初始化*/
    $('#index_tag').off('click', 'button', indexClickBtnTag);
    $('#index_tag').on('click', 'button', indexClickBtnTag);
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
    /** 设置button值*/
    let url_doc = '/ajax/doc.html';
    /** 加载doc.html, 完成后执行回调*/
    ajax_get(url_doc, docSuccess);
}
