<?php
session_start();

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

if (!isset($_SESSION['user_id'])) {
    echo 'param error user_id';
    return;
}

$tag = $_POST['note_tag'];
$title = $_POST['title'];
$content = $_POST['content'];

$nickname = $_SESSION['nickname'];
$user_id = $_SESSION['user_id'];

$time_stamp = time();

//TODO 插入数据前 检测collection 是否存在，不存在则不插入
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
/** collection name*/
$col_name = 'db_content.' . $tag;

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

/** 获取新用户id*/
$content_id = $response->value->content_id_now;

$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert(['content' => $content, 'editorid' => $user_id, 'editorname' => $nick_name, 'edittime' => $time_stamp]);

$res = $manager->executeBulkWrite($col_name, $bulk);

/** 修改对应文章最后编辑者的信息*/
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->update(
    ['contentid' => intval($content_id)],
    ['$set' => ['editorid' => $user_id, 'editorname' => $nick_name, 'edittime' => $time_stamp], '$inc' => ['commentcount' => 1]],
    ['multi' => false, 'upsert' => false]
);
$db_collection_name = 'db_tag.' . $tag;
$result = $manager->executeBulkWrite($db_collection_name, $bulk);

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

echo $tag;
echo '<br>';
echo $title;
echo '<br>';
echo $content;
echo '<br>';
echo $nickname;
echo '<br>';
echo $user_id;
echo '<br>';
echo $content_id;
echo '<br>';