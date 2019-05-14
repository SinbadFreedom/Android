<?php
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/4/4
 * Time: 16:50
 */
date_default_timezone_set('PRC');

/** 获取redis的排行榜数据*/
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
/** 日期相关的key与mongodb表对应*/
$time_stamp = time();
$today = date('Y-m-d', $time_stamp);
$yesterday = date('Y-m-d', $time_stamp - 24 * 60 * 60);
$year = date('Y', $time_stamp);
$month = date('m', $time_stamp);
/** 从1970年1月1日以来的总周数，方便计算上一周排名, 1970年1月1日是周四，减去4天的时间差，从19700105日，周一的时间差开始算总周数*/
$week = intval(($time_stamp - 4 * 24 * 60 * 60) / (7 * 24 * 60 * 60));
/** 日,周，月，经验数据表名*/
$col_yesterday = "col_day_" . $yesterday;
$col_week_last = "col_week_" . ($week - 1);
if ($month > 1) {
    $col_month_last = "col_month_" . $year . "_" . ($month - 1);
} else {
    $col_month_last = "col_month_" . ($year - 1) . "_" . 12;
}

$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

/** 获取对用排行榜数据,前100名 每日更新 昨日，上周，上月*/
/** ['val0' => 0, 'val2' => 2, 'val10' => 10] */
$res_2 = $redis->zrevrange($col_yesterday, 0, 99, true);
getUserInfo($res_2, $manager, $redis, "rank_yesterday");

$res_4 = $redis->zrevrange($col_week_last, 0, 99, true);
getUserInfo($res_4, $manager, $redis, "rank_week_last");

$res_6 = $redis->zrevrange($col_month_last, 0, 99, true);
getUserInfo($res_6, $manager, $redis, "rank_month_last");

/** 将玩家信息加入redis 排行榜中，更新redis排行榜数据*/
function getUserInfo($res, $manager, $redis, $redis_key)
{
    $info_arr = [];
    foreach ($res as $key => $value) {
        $filter = ['user_id' => $key];
        $options = ['limit' => 1];
        $query_find = new MongoDB\Driver\Query($filter, $options);
        $cursor = $manager->executeQuery('db_account.col_user', $query_find);
        $user_info = $cursor->toArray()[0];
        /** 插入玩家信息*/
        $info = new stdClass();
        $info->exp = $user_info->exp;
        $info->nickname = $user_info->nickname;
        $info->openid = $user_info->openid;
        $info->userid = $user_info->user_id;
        $info->headimgurl = $user_info->headimgurl;
        /** 将原玩家id转化为玩家信息对象, 存入数组*/
        array_push($info_arr, $info);
    }
    /** 存入redis*/
    $redis->set($redis_key, json_encode($info_arr));
    /** 输出*/
    var_dump($info_arr);
}