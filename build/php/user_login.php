<?php
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/3/27
 * Time: 11:27
 */
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');

$time_stamp = time();
$file = '../log/log_user_login_' . date('Y-m-d', $time_stamp) . '.txt';
$content = file_get_contents("php://input");
$content = $content . " $time_stamp\n";
file_put_contents($file, $content, FILE_APPEND);

if (!isset($_POST['openid'])) {
    echo "param error 0";
    return;
}

if (!isset($_POST['access_token'])) {
    echo "param error 1";
    return;
}

if (!isset($_POST['refresh_token'])) {
    echo "param error 2";
    return;
}

if (!isset($_POST['scope'])) {
    return;
}

if (!isset($_POST['headimgurl'])) {
    echo "param error 4";
    return;
}

if (!isset($_POST['nickname'])) {
    echo "param error 5";
    return;
}

if (!isset($_POST['sex'])) {
    echo "param error 6";
    return;
}

if (!isset($_POST['province'])) {
    echo "param error 7";
    return;
}

if (!isset($_POST['city'])) {
    echo "param error 8";
    return;
}

if (!isset($_POST['channel'])) {
    echo "param error 9";
    return;
}

if (!isset($_POST['logintype'])) {
    echo "param error 10";
    return;
}

$scope = $_POST['scope'];
$openid = $_POST['openid'];
$access_token = $_POST['access_token'];
$refresh_token = $_POST['refresh_token'];
$headimgurl = $_POST['headimgurl'];
$nickname = $_POST['nickname'];
$sex = $_POST['sex'];
$province = $_POST['province'];
$city = $_POST['city'];
$channel = $_POST['channel'];
$login_type = $_POST['logintype'];


$user_id = -1;
$exp = 1;
$is_new = false;
/** 根据openid 查找用户数据*/
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$filter = ['openid' => $openid];
$options = array(
    'limit' => 1
);
$query_find = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('db_account.col_user', $query_find);
$user_info = $cursor->toArray()[0];
/** 区分新老用户*/
if ($user_info) {
    /** 老用户*/
    $user_id = $user_info->user_id;
    $exp = $user_info->exp;
} else {
    /** 新用户*/
    $is_new = true;
    /** 生成自增id*/
    $query = array(
        "findandmodify" => "col_increase",
        "query" => ['table' => 'inc_user_id'],
        "update" => ['$inc' => ['user_id_now' => 1]],
        'upsert' => true,
        'new' => true,
        'fields' => ['user_id_now' => 1]
    );
    $command = new MongoDB\Driver\Command($query);
    $command_cursor = $manager->executeCommand('db_account', $command);
    $response = $command_cursor->toArray()[0];
    /** 获取新用户id*/
    $user_id = $response->value->user_id_now;
    /** 插入用户表*/
    $bulkInsertUser = new MongoDB\Driver\BulkWrite();
    $bulkInsertUser->insert([
        'openid' => $openid,
        'access_token' => $access_token,
        'refresh_token' => $refresh_token,
        'scope' => $scope,

        'headimgurl' => $headimgurl,
        'nickname' => $nickname,
        'sex' => $sex,
        'province' => $province,
        'city' => $city,

        'channel' => $channel,
        'user_id' => $user_id,
        'exp' => $exp,
        'exp_time' => $time_stamp,
        'create_time' => $time_stamp,
        'logintype' => $login_type
    ]);
    /** 插入数据库*/
    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 3000);
    $insertOneResult = $manager->executeBulkWrite('db_account.col_user', $bulkInsertUser, $writeConcern);
}
/** 返回用户id*/
$res = new stdClass;
$res->user_id = $user_id;
$res->is_new = $is_new;
$res->exp = $exp;

echo json_encode($res);