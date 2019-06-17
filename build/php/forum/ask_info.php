<?php
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');

/** 默认显示全部标签的文章，通过tag显示指定标签的文章*/
if (!isset($_GET['tag'])) {
    echo "param error tag";
    return;
}

if (!isset($_GET['language'])) {
    echo 'param error language';
    return;
}

if (!isset($_GET['contentid']) || !is_numeric($_GET['contentid'])) {
    echo "param error contentid";
    return;
}

require_once('../../php/util_curl.php');


$tag = $_GET['tag'];
$lan = $_GET['language'];
$content_id = intval($_GET['contentid']);

$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$col_name = 'db_tag.' . $tag;
$filter = ['contentid' => $content_id];
/** 只返回标题相关内容，不返回 _id*/
$options = ['limit' => 1, 'projection' => ['_id' => 0]];
/** 根据tag和content_id查找对应的文章标题信息*/
$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery($col_name, $query);

$info = $cursor->toArray()[0];
/** 加入tag信息*/
/** 返回数据*/
echo '--------------1';
echo '$col_name ' . $col_name;
echo '$tag ' . $tag;
echo '$content_id ' . $content_id;
echo '--------------2';
$res = new stdClass();
if ($info) {
    $info->tag = $tag;
    $res->state = 0;
    $res->info = $info;
} else {
    $res->state = -1;
}

echo json_encode($res);