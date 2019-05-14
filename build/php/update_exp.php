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

$time_stamp = time();
$today = date('Y-m-d', $time_stamp);
$file = '/workplace/log/log_update_exp_' . $today . '.log';
$content = file_get_contents("php://input");
$content = $content . " $time_stamp\n";
file_put_contents($file, $content, FILE_APPEND);

if (!isset($_SESSION['user_id'])) {
    return;
}

$user_id = $_SESSION['user_id'];
//$user_id = intval($_POST['userid']);
$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 3000);
/** 生成自增id*/
/** 每日经验上限是一天的分钟数*/
$exp_today_max = 60 * 24;
$query = [
    "findandmodify" => "col_user",
    "query" => ['user_id' => $user_id, 'exp_today' => ['$lt' => $exp_today_max]],
    "update" => ['$inc' => ['exp' => 1], '$inc' => ['exp_today' => 1]],
    'upsert' => false,
    'new' => true,
    'fields' => ['exp' => 1]
];

$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$command = new MongoDB\Driver\Command($query);
$command_cursor = $manager->executeCommand('db_account', $command);
$response = $command_cursor->toArray()[0];
/** 获取新用户id*/
$res = new stdClass();

if ($response->value) {
    /** 返回最新经验值*/
    $res->state = 0;
    $res->exp = $response->value->exp;

    /** 经验增量数据入库*/
    $year = date('Y', $time_stamp);
    $month = date('m', $time_stamp);
    /** 从1970年1月1日以来的总周数，方便计算上一周排名, 1970年1月1日是周四，减去4天的时间差，从19700105日，周一的时间差开始算总周数*/
    $week = intval(($time_stamp - 4 * 24 * 60 * 60) / (7 * 24 * 60 * 60));
    /** 日,周，月，经验数据表名*/
    $col_today = "col_day_" . $today;
    $col_week = "col_week_" . $week;
    $col_month = "col_month_" . $year . "_" . $month;

    updateExpAddValue($user_id, $manager, $col_today);
    updateExpAddValue($user_id, $manager, $col_week);
    updateExpAddValue($user_id, $manager, $col_month);
} else {
    $res->state = -1;
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