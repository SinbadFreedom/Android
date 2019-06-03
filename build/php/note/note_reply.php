<?php
session_start();
date_default_timezone_set('PRC');

$time_stamp = time();
$file = '/workplace/log/log_note_reply_' . date('Y-m-d', $time_stamp) . '.log';
$content = file_get_contents("php://input");
$content = $content . " $time_stamp\n";
file_put_contents($file, $content, FILE_APPEND);

$res = new stdClass();

if (!isset($_POST['tag'])) {
    $res->state = -1;
    $res->msg = 'param error tag';
    echo json_encode($res);
    return;
}

if (!isset($_POST['language'])) {
    $res->state = -2;
    $res->msg = 'param error language';
    echo json_encode($res);
    return;
}

if (!isset($_POST['content_id'])) {
    $res->state = -3;
    $res->msg = 'param error content_id';
    echo json_encode($res);
    return;
}

if (!isset($_POST['reply'])) {
    $res->state = -4;
    $res->msg = 'param error reply';
    echo json_encode($res);
    return;
}

if (!isset($_SESSION['nickname'])) {
    $res->state = -5;
    $res->msg = '请先登陆 nickname: ' . $_SESSION['nickname'];
    echo json_encode($res);
    return;
}

if (!isset($_SESSION['figure_url'])) {
    $res->state = -6;
    $res->msg = '请先登陆 figure_url: ' . $_SESSION['figure_url'];
    echo json_encode($res);
    return;
}

if (!isset($_SESSION['user_id'])) {
    $res->state = -7;
    $res->msg = '请先登陆 user_id: ' . $_SESSION['user_id'];
    echo json_encode($res);
    return;
}

/** GET参数*/
$tag = $_POST['tag'];
$language = $_POST['language'];
$content_id = intval($_POST['content_id']);
/** POST参数*/
$reply = $_POST['reply'];
/** SESSION参数*/
$nick_name = $_SESSION['nickname'];
$user_id = $_SESSION['user_id'];
$editor_figure = $_SESSION['figure_url'];
/** 时间戳*/
$time_stamp = time();

//TODO 插入数据前 检测collection 是否存在，不存在则不插入
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

/** 回复信息*/
$note_reply_info = [
    'content_id' => $content_id,
    'editor_id' => $user_id,
    'editor_name' => $nick_name,
    'editor_figure' => $editor_figure,
    'reply' => $reply,
    'edit_time' => $time_stamp,
    'good' => 0,
    'bad' => 0
];
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert($note_reply_info);
$note_collection_name = $tag . '_' . $language;
$col_reply_name = 'db_note.' . $note_collection_name;
$manager->executeBulkWrite($col_reply_name, $bulk);

$res->state = 0;
echo json_encode($res);