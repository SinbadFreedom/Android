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

/** 获取对用排行榜数据,前100名 每分钟定时更新 今日，本周，本月，全部*/
/** ['val0' => 0, 'val2' => 2, 'val10' => 10] */
$res_1 = $redis->zrevrange($col_today, 0, 99, true);
getUserInfo($res_1, $manager, $redis, "redis_rank_today");

$res_3 = $redis->zrevrange($col_week, 0, 99, true);
getUserInfo($res_3, $manager, $redis, "redis_rank_week");

$res_5 = $redis->zrevrange($col_month, 0, 99, true);
getUserInfo($res_5, $manager, $redis, "redis_rank_month");

/** 总榜单独处理*/
updateAllRank($manager, $redis, "redis_rank_all");

/** 将玩家信息加入redis 排行榜中，更新redis排行榜数据*/
function getUserInfo($res, $manager, $redis, $redis_key)
{
    $info_arr = [];
    foreach ($res as $key => $value) {
        $filter = ['user_id' => $key];
        $options = array(
            'limit' => 1
        );
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
    echo '$redis_key ' . $redis_key . '<br>';
    var_dump($info_arr);
    echo '<br>';
}

/**
 * 总榜直接查库，排序
 */
function updateAllRank($manager, $redis, $redis_key)
{
    $filter = [];
    $options = [
        'projection' => ['_id' => 0], /** 不输出_id字段*/
        'sort' => ['exp' => -1], /** 根据user_id字段排序 1是升序，-1是降序*/
        'limit' => 100
    ];
    $query_find = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery('db_account.col_user', $query_find);

    $rank_info = $cursor->toArray();
    $info_arr = [];
    foreach ($rank_info as $user_info) {
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
    echo 'redis_key: ' . $redis_key . '<br>';
    var_dump($info_arr);
    echo '<br>';
}
