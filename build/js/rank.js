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
    /** 加载排行榜数据*/
    let url = '/php/rank_list.php?type=' + type;
    ajax_get(url, rankInfoLoadSuccess);
}

/** 加载排行榜数据完成*/
function rankInfoLoadSuccess(res) {
    $('#rank_list_info').html(res);
}

/** 更新排行榜按钮状态*/
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
    $(id_str).addClass('active');
}
