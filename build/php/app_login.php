<?php
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/3/27
 * Time: 11:27
 */
session_start();

error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');

$time_stamp = time();
$file = '/workplace/log/log_app_login_' . date('Y-m-d', $time_stamp) . '.log';
$content = file_get_contents("php://input");
$content = $content . " $time_stamp\n";
file_put_contents($file, $content, FILE_APPEND);

if (!isset($_POST['openid'])) {
    echo "param error openid";
    return;
}

if (!isset($_POST['unionid'])) {
    echo "param error unionid";
    return;
}
//if (!isset($_POST['access_token'])) {
//    echo "param error 1";
//    return;
//}
//
//if (!isset($_POST['refresh_token'])) {
//    echo "param error 2";
//    return;
//}
//
//if (!isset($_POST['scope'])) {
//    return;
//}

if (!isset($_POST['headimgurl'])) {
    echo "param error headimgurl";
    return;
}

if (!isset($_POST['nickname'])) {
    echo "param error nickname";
    return;
}

//if (!isset($_POST['sex'])) {
//    echo "param error 6";
//    return;
//}
//
//if (!isset($_POST['province'])) {
//    echo "param error 7";
//    return;
//}
//
//if (!isset($_POST['city'])) {
//    echo "param error 8";
//    return;
//}

//if (!isset($_POST['channel'])) {
//    echo "param error 9";
//    return;
//}
//
//if (!isset($_POST['logintype'])) {
//    echo "param error 10";
//    return;
//}

//$scope = $_POST['scope'];
$open_id = $_POST['openid'];
$union_id = $_POST['unionid'];
//$access_token = $_POST['access_token'];
//$refresh_token = $_POST['refresh_token'];
$head_img_url = $_POST['headimgurl'];
$nick_name = $_POST['nickname'];
//$sex = $_POST['sex'];
//$province = $_POST['province'];
//$city = $_POST['city'];
//$channel = $_POST['channel'];
//$login_type = $_POST['logintype'];

require_once('mongo_login.php');
/** app登陆qq,wx都采用 unionid，web和app统一*/
$user_id = login($union_id, $open_id, $nick_name, $head_img_url);

/** 初始化$_SESSION 数据*/
$_SESSION['figure_url'] = str_replace('http://', 'https://', $_POST['headimgurl']);
$_SESSION['nickname'] = $nick_name;
$_SESSION['user_id'] = $user_id;

/** 返回用户id*/
$res = new stdClass;
$res->user_id = $user_id;

echo json_encode($res);