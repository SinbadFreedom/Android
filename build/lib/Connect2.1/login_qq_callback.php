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

/**
{
"client_id":"YOUR_APPID",
"openid":"YOUR_OPENID",
"unionid":"YOUR_UNIONID"
}
 */

require_once('../../php/util_curl.php');
$union_id_url = 'https://graph.qq.com/oauth2.0/me?access_token=' . $access_token . '&unionid=1';
echo '-----------1';
//$union_id_obj = $qc_info->getUnionId();
$data = getDataFromUrl($union_id_url);
/**
 * 返回字符串
 * callback( {"client_id":"101576583","openid":"B3EEFF9E07DD30D2E89FEE4C988B8F7F","unionid":"UID_A86FD72CF72028D3332634A0A0C9A22F"} );
 * 需要去掉 callback( 和  );
 */
var_dump($data);
//$data.re
str_replace('callback(','',$data);
str_replace(');','',$data);
echo '-----------1+';
var_dump($data);
echo '-----------1+++';
$data_json = json_decode($data);
//$access_token = $data_json->access_token;
//$open_id = $data_json->openid;
var_dump($data_json);
echo '-----------2';
$union_id = $data_json->unionid;
$client_id = $data_json->client_id;
echo $union_id;
echo '-----------3';

if (!$union_id || !$open_id) {
    echo 'union_id  or open_id error.';
    return;
}

require_once('../../php/mongo_login.php');

$user_id = login($union_id, $open_id, $nick_name, $head_img_url);
echo $user_id;
echo '-----------4';
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
//header("Location: $from_url");
return;
