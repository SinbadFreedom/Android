<?php
echo '------------------0';
if (!isset($_GET['code'])) {
    echo 'code false';
    return;
}

$code = $_GET['code'];

echo '------------------1';
var_dump($code);
echo '------------------2';

$url = 'https' . '://ap' . 'i.we' . 'ix' . 'in.q' . 'q.c' . 'om/sn' . 's/oau' . 'th2/ac' . 'cess_' . 'to' . 'ken?ap' . 'pid=wx7' . 'c369' . 'f8fe5340534&sec' . 'ret' . '=' . '163ffc' . 'e441b47' . '6cd12672b69' . '51889ddf' . '&co' . 'de=' . $code . '&grant_type' . '=' . 'au' . 'thor' . 'izatio' . 'n_' . 'code';
var_dump($url);
$data = getDataFromUrl($url);
echo '------------------3';
var_dump($data);
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
//$access_token = '';
//$openid = '';

$url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $open_id;
echo '------------------4';
var_dump($url);

$info = getDataFromUrl($url);
echo '------------------5';
var_dump($info);
$info_json = json_decode($info);
echo '------------------6';
var_dump($info_json);
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
function getDataFromUrl($get_url)
{
    $curl = curl_init();
    /** 设置抓取的url*/
    curl_setopt($curl, CURLOPT_URL, $get_url);
    /** 设置头文件的信息作为数据流输出*/
    curl_setopt($curl, CURLOPT_HEADER, 1);
    /** 设置获取的信息以文件流的形式返回，而不是直接输出。*/
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    /** 执行命令*/
    $data = curl_exec($curl);
    /** 关闭URL请求*/
    curl_close($curl);
    return $data;
}