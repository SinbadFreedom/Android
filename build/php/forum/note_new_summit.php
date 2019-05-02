<?php

var_dump($_POST);

if (!isset($_POST['note_tag'])) {
    return;
}

if (!isset($_POST['title'])) {
    return;
}

if (!isset($_POST['content'])) {
    return;
}

if (!isset($_SESSION['nickname'])) {
    echo 'param error nickname';
    return;
}

if (!isset($_SESSION['figureurl_qq'])) {
    echo 'param error figureurl_qq';
    return;
}

$tag = $_POST['note_tag'];
$title = $_POST['title'];
$content = $_POST['content'];
$nickname = $_POST['nickname'];
$time_stamp = time();

//TODO 插入数据前 检测collection 是否存在，不存在则不插入
/** collection name*/
$col_name = 'db_content.' . $tag;
/** 初始化content_id*/
// TODO increase $content_id

$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert(['content' => $content, 'editor_name' => $nick_name, 'edit_time' => $time_stamp]);

$res = $manager->executeBulkWrite($col_name, $bulk);

///** 修改对应文章最后编辑者的信息*/
//$bulk = new MongoDB\Driver\BulkWrite;
//$bulk->update(
//    ['contentid' => intval($content_id)],
//    ['$set' => ['editorid' => $user_id, 'editorname' => $nick_name, 'edittime' => $time_stamp],
//        '$inc' => ['commentcount' => 1]],
//    ['multi' => false, 'upsert' => false]
//);
//$db_collection_name = 'db_tag.' . $tag;
//$result = $manager->executeBulkWrite($db_collection_name, $bulk);

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

