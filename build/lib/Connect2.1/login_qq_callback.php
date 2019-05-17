<?php
require_once("API/qqConnectAPI.php");
/** 获取 token 和 open_id */
$qc = new QC();
$access_token = $qc->qq_callback();
$open_id = $qc->get_openid();
/** 获取用户信息*/
$qc_info = new QC($access_token, $open_id);
$arr = $qc_info->get_user_info();
/**
 * B95E1C6C1D39AF7978AEBE978633382E
 * B1B2F5859B8CDE96F93531960D2D176B
 * Array ( [ret] => 0 [msg] => [is_lost] => 0 [nickname] => Sinbad [gender] => 男 [province] => 北京 [city] => [year] => 1982 [constellation] => [figureurl] => http://qzapp.qlogo.cn/qzapp/101569922/B1B2F5859B8CDE96F93531960D2D176B/30 [figureurl_1] => http://qzapp.qlogo.cn/qzapp/101569922/B1B2F5859B8CDE96F93531960D2D176B/50 [figureurl_2] => http://qzapp.qlogo.cn/qzapp/101569922/B1B2F5859B8CDE96F93531960D2D176B/100 [figureurl_qq_1] => http://thirdqq.qlogo.cn/g?b=oidb&k=mwBhEnLgHCziaHdoTau4KWw&s=40 [figureurl_qq_2] => http://thirdqq.qlogo.cn/g?b=oidb&k=mwBhEnLgHCziaHdoTau4KWw&s=100 [figureurl_qq] => http://thirdqq.qlogo.cn/g?b=oidb&k=mwBhEnLgHCziaHdoTau4KWw&s=140 [figureurl_type] => 1 [is_yellow_vip] => 0 [vip] => 0 [yellow_vip_level] => 0 [level] => 0 [is_yellow_year_vip] => 0 )
 *
 * $arr[nickname];
 * $arr[gender];
 * $arr[province];
 * $arr[city];
 * $arr[year];
 * $arr[figureurl_qq];
 * $arr[figureurl_type];
 */

/** 数据入库，返回user_id*/
$head_img_url = $arr['figureurl_qq'];
$nick_name = $arr['nickname'];
$sex = $arr['gender'];
$province = $arr['province'];
$city = $arr['city'];
$year = $arr['year'];
$figureurl_type = $arr['figureurl_type'];

require_once('../../php/mongo_login.php');
//TODO QQ 网站应用审核通过后，点击申请获取unionid,替换为unionid
$user_id = login($open_id, $nick_name, $head_img_url);

if ($user_id < 0) {
    echo 'userid error 请重新登陆';
    return;
}

/** 初始化$_SESSION 数据*/
$_SESSION['figure_url'] = str_replace('http://', 'https://', $arr['figureurl_qq']);
$_SESSION['nickname'] = $arr['nickname'];
$_SESSION['user_id'] = $user_id;

/** login_ui.php中记录来路url，完成登陆，跳转回去*/
$from_url = $_SESSION['from_url'];
header("Location: $from_url");
return;
