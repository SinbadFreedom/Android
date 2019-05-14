<?php
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/4/4
 * Time: 16:50
 */
date_default_timezone_set('PRC');

require_once('../util_time.php');

/** 获取redis的排行榜数据*/
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

/** 获取对用排行榜数据,前100名 每日更新 昨日，上周，上月*/
/** ['val0' => 0, 'val2' => 2, 'val10' => 10] */
$res_2 = $redis->zrevrange($col_yesterday, 0, 99, true);
getUserInfo($res_2, $manager, $redis, "redis_rank_yesterday");

$res_4 = $redis->zrevrange($col_week_last, 0, 99, true);
getUserInfo($res_4, $manager, $redis, "redis_rank_week_last");

$res_6 = $redis->zrevrange($col_month_last, 0, 99, true);
getUserInfo($res_6, $manager, $redis, "redis_rank_month_last");

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