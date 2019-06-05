<?php
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/3/15
 * Time: 12:20
 */
session_start();

error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');

if (!isset($_GET['tag'])) {
    echo 'param error tag';
    return;
}

if (!isset($_GET['language'])) {
    echo 'param error language';
    return;
}

if (!isset($_GET['contentid'])) {
    echo 'param error contentid3';
    return;
}

if (!is_numeric($_GET['contentid'])) {
    echo 'param error contentid4';
    return;
}

/** 回复翻页，默认显示第一页*/
$page = 0;
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = intval($_GET['page']);
    $page = $page - 1;

    if ($page < 0) {
        $page = 0;
    }
}

$tag = $_GET['tag'];
$language = $_GET['language'];
$content_id = intval($_GET['contentid']);

$file = '/workplace/log/log_note_get_' . date('Y-m-d', $time_stamp) . '.log';
$content = $tag . $language . " $content_id " . " $time_stamp\n";
file_put_contents($file, $content, FILE_APPEND);
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

$note_collection_name = $tag . '_' . $language;

/** 笔记翻页*/
$count_per_page = 10;

$command = new MongoDB\Driver\Command([
    /** $tag 是 collection name */
    'count' => $note_collection_name,
    'query' => ['content_id' => $content_id]
]);

$command_cursor = $manager->executeCommand('db_note', $command);
/** 笔记总条数 列表分页用*/
$total_count = $command_cursor->toArray()[0]->n;
$page_max = floor($total_count / $count_per_page);
if ($total_count > 0) {
    /** 获取指定区间，分页用*/
    if ($page > $page_max) {
        /** 上边界保护*/
        $page = $page_max;
    }
} else {
    $page = 0;
}

/** 第一页不显示last*/
$page_last = $page - 1 < 0 ? 0 : $page - 1;

$page_next = $page + 1 > $page_max ? $page_max : $page + 1;
if ($page_next == $page_max) {
    /** 最后一页不显示next*/
    $page_next = 0;
}

$filter = ['content_id' => $content_id];
/** 只返回标题相关内容，不返回文章内容*/
$options = [
    /** 按时间倒叙排列*/
    'sort' => ['_id' => -1],
    /** 显示数量控制 */
    'limit' => $count_per_page,
    /** 分页使用 */
    'skip' => $count_per_page * $page
];
/** 根据tag和content_id查找对应的文章标题信息*/
$query = new MongoDB\Driver\Query($filter, $options);
$col_reply_name = 'db_note.' . $note_collection_name;
$cursor = $manager->executeQuery($col_reply_name, $query);

$reply_info = [];
foreach ($cursor as $document) {
    /**
     * 回复数据
     * 'editor_id' => $user_id,
     * 'editor_name' => $nick_name,
     * 'editor_figure' => $editor_figure,
     * 'reply' => $reply,
     * 'edit_time' => $time_stamp,
     */
    $info = new stdClass();
    $info->editor_id = $document->editor_id;
    $info->editor_name = $document->editor_name;
    $info->editor_figure = $document->editor_figure;
    $info->edit_time = date("m-d H:i", $document->edit_time);
    $info->reply = $document->reply;

    array_push($reply_info, $info);
}

$res = new stdClass();
$res->notes = $reply_info;
$res->page_next = $page_max > $page;
echo json_encode($res);