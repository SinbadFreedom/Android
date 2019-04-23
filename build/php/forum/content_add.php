<?php
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/3/20
 * Time: 1:03
 */
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');

$time_stamp = time();
$file = '../../log/log_content_add_' . date('Y-m-d', $time_stamp) . '.txt';
$content = file_get_contents("php://input");
$content = $content . " $time_stamp\n";
file_put_contents($file, $content, FILE_APPEND);

if (!isset($_POST['contentid'])) {
    echo 'param error 0';
    return;
}

if (!is_numeric($_POST['contentid'])) {
    echo 'param error 6';
    return;
}

if (!isset($_POST['content'])) {
    echo 'param error 1';
    return;
}

if (!isset($_POST['userid'])) {
    echo 'param error 3';
    return;
}

if (!isset($_POST['name'])) {
    echo 'param error 4';
    return;
}

if (!isset($_POST['tag'])) {
    echo 'param error 5';
    return;
}

$tag = $_POST['tag'];
$content_id = $_POST['contentid'];
$content = $_POST['content'];
$user_id = $_POST['userid'];
$nick_name = $_POST['name'];

//TODO 插入数据前 检测collection 是否存在，不存在则不插入
/** collection name*/
$col_name = 'db_content.'. $tag . '_' . $content_id;

$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert(['content' => $content, 'editorid' => $user_id, 'editorname' => $nick_name, 'edittime' => $time_stamp]);

//$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 3000);
//$res = $manager->executeBulkWrite($col_name, $bulk, $writeConcern);
$res = $manager->executeBulkWrite($col_name, $bulk);

/** 修改对应文章最后编辑者的信息*/
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->update(
    ['contentid' => intval($content_id)],
    ['$set' => ['editorid' => $user_id, 'editorname' => $nick_name, 'edittime' => $time_stamp]],
    ['multi' => false, 'upsert' => false]
);
$db_collection_name = 'db_tag.' . $tag;
$result = $manager->executeBulkWrite($db_collection_name, $bulk);

/** 更新redis的编辑时间 加入排序列表*/
$content_all = "content_all";
//$content_tag = "content_" . $tag;
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
/**
 * 全部文章更新排序,
 * $score1 是编辑时间
 * $value1 是$tag和$content_id的组合 $tag_$content_id
 */
$redis->zAdd($content_all, $time_stamp, $tag . '_' .$content_id);
///** 指定tag更新排序*/
//$redis->zAdd($content_tag, $time_stamp, $tag . '_' .$content_id);