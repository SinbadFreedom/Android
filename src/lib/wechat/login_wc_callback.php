<?php
session_start();

if (!isset($_GET['code'])) {
    echo 'code false';
    return;
}

$code = $_GET['code'];


//function getDataFromUrl($get_url)
//{
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_URL, $get_url);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLOPT_HEADER, 0);
//    $data = curl_exec($ch);
//    curl_close($ch);
//    return $data;
//}
require_once('../../php/util_curl.php');

$url = 'https' . '://ap' . 'i.we' . 'ix' . 'in.q' . 'q.c' . 'om/sn' . 's/oau' . 'th2/ac' . 'cess_' . 'to' . 'ken?ap' . 'pid=wx7' . 'c369' . 'f8fe5340534&sec' . 'ret' . '=' . '163ffc' . 'e441b47' . '6cd12672b69' . '51889ddf' . '&co' . 'de=' . $code . '&grant_type' . '=' . 'au' . 'thor' . 'izatio' . 'n_' . 'code';
$data = getDataFromUrl($url);

$data_json = json_decode($data);
$access_token = $data_json->access_token;
$open_id = $data_json->openid;

/**
 * {
 * "access_token":"ACCESS_TOKEN",
 * "expires_in":7200,
 * "refresh_token":"REFRESH_TOKEN",
 * "openid":"OPENID",
 * "scope":"SCOPE",
 * "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
 * }
 */
/** 暂时不执行refresh_token，直接用上一步的$access_token*/
$refresh_token = '';
$url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=wx7c369f8fe5340534&grant_type=refresh_token&refresh_token=' . $refresh_token;

/**
 * {
 * "access_token":"ACCESS_TOKEN",
 * "expires_in":7200,
 * "refresh_token":"REFRESH_TOKEN",
 * "openid":"OPENID",
 * "scope":"SCOPE"
 * }
 */
$url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $open_id;

$info = getDataFromUrl($url);
/**
 * {
 * "openid":"OPENID",
 * "nickname":"NICKNAME",
 * "sex":1,
 * "province":"PROVINCE",
 * "city":"CITY",
 * "country":"COUNTRY",
 * "headimgurl": "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0",
 * "privilege":[
 * "PRIVILEGE1",
 * "PRIVILEGE2"
 * ],
 * "unionid": " o6_bmasdasdsad6_2sgVt7hMZOPfL"
 *
 * }
 *
 */
$info_json = json_decode($info);
$info_json->sex;
$info_json->province;
$info_json->city;
$info_json->country;
$info_json->unionid;

$open_id = $info_json->openid;
$nick_name = $info_json->nickname;
$head_img_url = $info_json->headimgurl;
$union_id = $info_json->unionid;

require_once('../../php/mongo_login.php');
/** 微信登陆采用 unionid，web和app统一*/
$user_id = login($union_id, $open_id, $nick_name, $head_img_url);

if ($user_id < 0) {
    echo 'userid error 请重新登陆';
    return;
}

/** 初始化$_SESSION 数据*/
$_SESSION['figure_url'] = str_replace('http://', 'https://', $head_img_url);
$_SESSION['nickname'] = $nick_name;
$_SESSION['user_id'] = $user_id;
$_SESSION['union_id'] = $user_id;

/** login_ui.php中记录来路url，完成登陆，跳转回去*/
$from_url = '/index.html';
header("Location: $from_url");
return;

