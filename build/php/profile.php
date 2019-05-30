<?php
session_start();
require_once('util_level.php');

if (!isset($_SESSION['user_id'])) {
    echo "请先登录 user_id";
    return;
}

$user_id = intval($_SESSION['user_id']);

$res = new stdClass();

if ($user_id) {
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter = ['user_id' => $user_id];
    $options = ['limit' => 1];
    $query_find = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery('db_account.col_user', $query_find);
    $user_info = $cursor->toArray()[0];
    /** 用户显示信息*/
    $nick_name = $user_info->nickname;
    $exp = $user_info->exp;
    $create_time = $user_info->create_time;
    /** 计算等级和最大经验*/
    $level = getLevelByExp($exp);
    $exp_max = getLevelExpMax($level);
    $percent = intval($exp * 100 / $exp_max);

    $res->state = 0;
    $res->id = $user_id;
    $res->name = $user_info->nickname;
    $res->exp = $user_info->exp;
    $res->create_time = date('Y-m-d', $user_info->create_time);
    $res->level = $level;
    $res->exp_max = $exp_max;
    $res->percent = $percent;
} else {
    $res->state = -1;
}

echo json_encode($res);