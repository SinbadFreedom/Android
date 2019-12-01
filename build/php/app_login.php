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

if (!isset($_POST['headimgurl'])) {
    echo "param error headimgurl";
    return;
}

if (!isset($_POST['nickname'])) {
    echo "param error nickname";
    return;
}

$open_id = $_POST['openid'];
$union_id = $_POST['unionid'];
$head_img_url = $_POST['headimgurl'];
$nick_name = $_POST['nickname'];
$url_reload = $_POST['url_reload'];

require_once('mongo_login.php');
/** app登陆qq,wx都采用 unionid，web和app统一*/
$user_id = login($union_id, $open_id, $nick_name, $head_img_url);

/** 初始化$_SESSION 数据*/
$_SESSION['figure_url'] = str_replace('http://', 'https://', $_POST['headimgurl']);
$_SESSION['nickname'] = $nick_name;
$_SESSION['user_id'] = $user_id;

header('content-type:text/html;charset=utf-8');
/** 立即跳转至目标页面*/
echo '<script>window.location.href="' . $url_reload . '";</script>';