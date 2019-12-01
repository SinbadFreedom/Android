<?php
session_start();
date_default_timezone_set('PRC');

if (!isset($_POST['tag'])) {
    echo 'param error tag';
    return;
}

if (!isset($_POST['title'])) {
    echo 'param error title';
    return;
}

if (!isset($_POST['content'])) {
    echo 'param error content';
    return;
}

if (!isset($_SESSION['nickname'])) {
    echo 'param error nickname';
    return;
}

if (!isset($_SESSION['figure_url'])) {
    echo 'param error figure_url';
    return;
}

if (!isset($_SESSION['user_id'])) {
    echo 'param error user_id';
    return;
}

$tag = $_POST['tag'];
$title = $_POST['title'];
$content = $_POST['content'];

$nick_name = $_SESSION['nickname'];
$user_id = $_SESSION['user_id'];

$time_stamp = time();

//TODO 插入数据前 检测collection 是否存在，不存在则不插入
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

/** 初始化content_id*/
/** 生成自增id*/
$query = array(
    "findandmodify" => "col_increase",
    "query" => ['table' => 'inc_content_id'],
    "update" => ['$inc' => ['content_id_now' => 1]],
    'upsert' => true,
    'new' => true,
    'fields' => ['content_id_now' => 1]
);
$command = new MongoDB\Driver\Command($query);
$command_cursor = $manager->executeCommand('db_account', $command);
$response = $command_cursor->toArray()[0];
$content_id = $response->value->content_id_now;

/** 标题信息*/
$note_title_info = [
    'contentid' => $content_id,
    'authorid' => $user_id,
    'authorname' => $nick_name,
    'author_figure' => $_SESSION['figure_url'],
    'title' => $title,
    'content' => $content,
    'createtime' => $time_stamp,
    'comment_count' => 0
];
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert($note_title_info);
$db_collection_name = 'db_tag.' . $tag;
$manager->executeBulkWrite($db_collection_name, $bulk);

/** 更新redis的编辑时间 加入排序列表*/
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
/**
 * 全部文章更新排序,
 * $score1 是编辑时间
 * $value1 是$tag和$content_id的组合 $tag_$content_id
 */
$redis->zAdd('content_all', $time_stamp, $tag . '_' . $content_id);
/** 指定tag更新排序*/
$redis->zAdd($tag, $time_stamp, $tag . '_' . $content_id);

/** 更新经验*/
require_once('../update_exp.php');

/** 返回数据*/
$res = new stdClass();
$res->state = 0;
$res->tag = $tag;
$res->content_id = $content_id;
echo json_encode($res);
