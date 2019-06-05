<?php
session_start();
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/3/27
 * Time: 10:02
 */

require_once('util_level.php');
require_once('util_time.php');

$TYPE_TODAY = 'rank_1';
$TYPE_YESTERDAY = 'rank_2';
$TYPE_WEEK = 'rank_3';
$TYPE_WEEK_LAST = 'rank_4';
$TYPE_MONTH = 'rank_5';
$TYPE_MONTH_LAST = 'rank_6';
$TYPE_ALL = 'rank_7';

/** 排行榜类型 默认今日*/
$type = $TYPE_TODAY;
if (isset($_GET['type'])) {
    $type = $_GET['type'];
}

$file = '/workplace/log/log_rank_list_' . $today . '.log';
if (isset($_GET['userid'])) {
    $user_id = $_GET['userid'];
    $content = ' userid ' . $user_id . ' type ' . $type . " $time_stamp\n";
} else {
    $content = ' type ' . $type . " $time_stamp\n";
}
file_put_contents($file, $content, FILE_APPEND);

/** 获取redis的排行榜数据*/
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$key = null;
$title = '';
$tip = '';

switch ($type) {
    case $TYPE_TODAY:
        $key = 'redis_rank_today';
        $title = '今日排行榜';
        $tip = '每分钟更新';
        break;
    case $TYPE_YESTERDAY:
        $key = 'redis_rank_yesterday';
        $title = '昨日排行榜';
        $tip = '每日0点更新';
        break;
    case $TYPE_WEEK:
        $key = 'redis_rank_week';
        $title = '本周排行榜';
        $tip = '每分钟更新';
        break;
    case $TYPE_WEEK_LAST:
        $key = 'redis_rank_week_last';
        $title = '上周排行榜';
        $tip = '每周一0点更新';
        break;
    case $TYPE_MONTH:
        $key = 'redis_rank_month';
        $title = '本月排行榜';
        $tip = '每分钟更新';
        break;
    case $TYPE_MONTH_LAST:
        $key = 'redis_rank_month_last';
        $title = '上月排行榜';
        $tip = '每月1日0点更新';
        break;
    case $TYPE_ALL:
        $key = 'redis_rank_all';
        $title = '总排行榜';
        $tip = '每分钟更新';
        break;
}

if (!$key) {
    return;
}

$rank_info_str = $redis->get($key);
if ($rank_info_str) {
    $rank_info_obj = json_decode($rank_info_str);
}
//echo $rank_info_str;
if ($rank_info_obj && count($rank_info_obj) > 0) {
    $rank = 1;
    foreach ($rank_info_obj as $value) {
        /** https替换http*/
        $headimgurl = str_replace('http://', 'https://', $value->headimgurl);
        $value->headimgurl = $headimgurl;
//    $nick_name = $value->nickname;
        $exp = $value->exp;
        /** 计算等级*/
        $value->level = getLevelByExp($exp);
        /** 排名*/
        $value->rank = $rank;
        $rank++;
    }
}

$rank_list = json_decode($rank_info_obj);

$res = new stdClass();
$res->tip = $title . '  ' . $tip;
$res->info = $rank_info_obj;
echo json_encode($res);