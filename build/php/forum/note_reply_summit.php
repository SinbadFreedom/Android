<?php
session_start();
date_default_timezone_set('PRC');

if (!isset($_GET['tag'])) {
    echo 'param error tag';
    return;
}

if (!isset($_GET['contentid'])) {
    echo 'param error contentid';
    return;
}

if (!isset($_POST['reply'])) {
    echo 'param error reply';
    return;
}

if (!isset($_SESSION['nickname'])) {
    echo '请先登陆';
    return;
}

if (!isset($_SESSION['figureurl_qq'])) {
    echo '请先登陆';
    return;
}

if (!isset($_SESSION['user_id'])) {
    echo '请先登陆';
    return;
}

/** GET参数*/
$tag = $_GET['tag'];
$content_id = intval($_GET['contentid']);
/** POST参数*/
$reply = $_POST['reply'];
/** SESSION参数*/
$nick_name = $_SESSION['nickname'];
$user_id = $_SESSION['user_id'];
$editor_figure = $_SESSION['figureurl_qq'];
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
    'edit_time' => $time_stamp
];
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert($note_reply_info);
$col_reply_name = 'db_reply.' . $tag;
$manager->executeBulkWrite($col_reply_name, $bulk);

/** 修改最后编辑相关的标题信息，增加编辑次数*/
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->update(
    ['contentid' => $content_id],
    ['$set' => ['editor_id' => $user_id, 'editor_name' => $nick_name, 'edit_time' => $time_stamp],
        '$inc' => ['comment_count' => 1]],
    ['multi' => false, 'upsert' => false]
);
$col_tag_name = 'db_tag.' . $tag;
$manager->executeBulkWrite($col_tag_name, $bulk);

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

/** 跳转到指定评论页面*/
$url = 'note_get.php?tag=' . $tag . '&contentid=' . $content_id;
echo '<script language = "javascript" type = "text/javascript">window.location.href="' . $url . '"</script>';