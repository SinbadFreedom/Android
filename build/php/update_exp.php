<?php
session_start();
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/4/3
 * Time: 16:42
 */
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');

/** 登录状态检测*/
if (!isset($_SESSION['user_id'])) {
    return;
}
$user_id = $_SESSION['user_id'];

require_once('util_time.php');

$file = '/workplace/log/log_update_exp_' . $today . '.log';
$content = $user_id . " $time_stamp\n";
file_put_contents($file, $content, FILE_APPEND);

$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 3000);
/** 生成自增id*/
/** 每日经验上限是一天的分钟数, exp_today 记录当天获取的经验数，通过crontab/reset_exp_day.php 每日0点重置*/
$exp_today_max = 60 * 24;

$query = [
    "findandmodify" => "col_user",
    "query" => ['user_id' => $user_id, 'exp_day' => ['$lt' => $exp_today_max]],
    "update" => ['$inc' => ['exp' => 1, 'exp_day' => 1]],
    'upsert' => false,
    'new' => true,
    'fields' => ['exp' => 1]
];

$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$command = new MongoDB\Driver\Command($query);
$command_cursor = $manager->executeCommand('db_account', $command);
$response = $command_cursor->toArray()[0];

/** 获取新用户id*/

if ($response->value) {
    updateExpAddValue($user_id, $manager, $col_today);
    updateExpAddValue($user_id, $manager, $col_week);
    updateExpAddValue($user_id, $manager, $col_month);
}

function updateExpAddValue($user_id, $manager, $col_name)
{
    $query = [
        "findandmodify" => $col_name,
        "query" => ['user_id' => $user_id],
        "update" => ['$inc' => ['exp' => 1]],
        'upsert' => true,
        'new' => true,
        'fields' => ['exp' => 1]
    ];

    $command = new MongoDB\Driver\Command($query);
    $command_cursor = $manager->executeCommand('db_rank_list', $command);
    $response = $command_cursor->toArray()[0];
    $exp = $response->value->exp;

    /** 更新redis的值*/
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    /** 加入有序集合*/
    $redis->zAdd($col_name, $exp, $user_id);
    return $exp;
}