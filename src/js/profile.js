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
}