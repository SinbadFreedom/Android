<?php
session_start();

if (!isset($_GET['code'])) {
    echo 'code false';
    return;
}

$code = $_GET['code'];


function getDataFromUrl($get_url)
{
    $curl = curl_init($get_url);
    $ret = curl_exec($curl);
    $data = curl_getinfo($curl);
    curl_close($curl);
    return $data;
}

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
$info['sex'];
$info['province'];
$info['city'];
$info['country'];
$info['unionid'];

$open_id = $info['openid'];
$nick_name = $info['nickname'];
$head_img_url = $info['headimgurl'];

var_dump($info);
echo '<br>';
echo $open_id;
echo '<br>';
echo $nick_name;
echo '<br>';
echo $head_img_url;

require_once('../../php/mongo_login.php');
$user_id = login($open_id, $nick_name, $head_img_url);

echo '<br>';
echo $user_id;
echo '<br>';

if ($user_id < 0) {
    echo 'userid error 请重新登陆';
    return;
}

/** 初始化$_SESSION 数据*/
$_SESSION['figure_url'] = str_replace('http://', 'https://', $head_img_url);
$_SESSION['nickname'] = $nick_name;
$_SESSION['user_id'] = $user_id;

/** login_ui.php中记录来路url，完成登陆，跳转回去*/
$from_url = $_SESSION['from_url'];
//header("Location: $from_url");
//return;

