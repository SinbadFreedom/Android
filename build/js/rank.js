/** 排行模板数据，对应/hbs/rank.hbs*/
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
