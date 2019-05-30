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
    let name = res.name;
    let id = res.id;
    let exp = res.exp;
    let create_time = res.create_time;
    let level = res.level;
    let exp_max = res.exp_max;
    let percent = res.percent;

    $('#profile_name').attr('value', name);
    $('#profile_id').attr('value', id);
    $('#profile_create_time').attr('value', create_time);
    $('#profile_level').attr('value', level);
    $('#profile_exp').attr('value', exp + '/' + exp_max);
    /** 经验条*/
    $('#profile_progress').css('width', percent + '%');
    $('#profile_progress').attr('aria-valuenow', exp);
    $('#profile_progress').attr('aria-valuemax', exp_max);
}