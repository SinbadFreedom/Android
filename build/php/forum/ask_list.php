<?php
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');

/** 默认显示全部标签的文章，通过tag显示指定标签的文章*/
$tag = 'content_all';
if (isset($_GET['tag'])) {
    $tag = $_GET['tag'];
}

if (!isset($_GET['language'])) {
    echo 'param error language';
    return;
}

$lan = $_GET['language'];

/** 分页显示，默认page从0开始，显示页数时加1*/
$page = 0;
if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
    /** 下边界保护*/
    if ($page < 0) {
        $page = 0;
    }
}

/** 每页显示条数*/
$count_per_page = 20;

$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

/** 获取总数 文章列表分页用*/
$total_count = $redis->zcard($tag);
$page_max = ceil($total_count / $count_per_page);
/** 获取指定区间，分页用*/
if ($page > $page_max) {
    /** 上边界保护*/
    $page = $page_max;
}

/** 标题列表 默认按最后编辑时间读取最新20篇 编辑时间降序排序*/
$start = $count_per_page * $page;
$res = $redis->zrevrange($tag, $start, $start + $count_per_page - 1, true);

$ask_arr = [];
foreach ($res as $key => $value) {
    /** $key 格式 Python3.7.2_2 $tag_$content_id*/
    $pos = strrpos($key, '_');
    $tag = substr($key, 0, $pos);
    $content_id_str = substr($key, $pos + 1);
    $content_id = intval($content_id_str);
    $col_name = 'db_tag.' . $tag;
    $filter = ['contentid' => $content_id];
    /** 只返回标题相关内容，不返回文章内容和_id*/
    $options = ['limit' => 1, 'projection' => ['content' => 0, '_id' => 0]];
    /** 根据tag和content_id查找对应的文章标题信息*/
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery($col_name, $query);
    $info = $cursor->toArray()[0];

    /** 加入tag信息*/
    $info->tag = $tag;
    /**
     * 点击新窗口打开链接 url 示例：
     * /index.html?tag=%E6%8A%80%E6%9C%AF%E8%AE%A8%E8%AE%BA&language=zh_cn&contentid=100051&nav=ask
     */
    $info->url_content = '/index.html?nav=ask&tag=' . $tag . '&language=' . $lan . '&contentid=' . $content_id;
    array_push($ask_arr, $info);
}

$res = new stdClass();
$res->ask = $ask_arr;

echo json_encode($res);