<?php
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/3/15
 * Time: 12:20
 */
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');

if (!isset($_GET['contentid'])) {
    echo 'param error contentid3';
    return;
}

if (!is_numeric($_GET['contentid'])) {
    echo 'param error contentid4';
    return;
}

if (!isset($_GET['tag'])) {
    echo 'param error tag';
    return;
}

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

$tag = $_GET['tag'];
$content_id = $_GET['contentid'];

$time_stamp = time();
$file = '/workplace/log/log_content_get_' . date('Y-m-d', $time_stamp) . '.log';
$content = "$tag " . " $content_id" . " $time_stamp\n";;
file_put_contents($file, $content, FILE_APPEND);/** collection name*/;
$db_name = 'db_content';
$col_name = $tag . '_' . $content_id;
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

$query = [
    'count' => $col_name,
    'query' => [],
];
$command = new MongoDB\Driver\Command($query);
$command_cursor = $manager->executeCommand($db_name, $command);
/** 笔记总条数 列表分页用*/
$total_count = $command_cursor->toArray()[0]->n;

/** 获取指定区间，分页用*/
if ($count_per_page * $page > $total_count) {
    /** 上边界保护*/
    $page = intval($total_count / $count_per_page);
}

/** 页数*/
$page_current = $page + 1;
$page_before = $page_current - 1;
$page_after = $page_current + 1;
$page_current_str = '<li class="page-item"><a class="page-link" href="/php/forum/content_get.php?tag=' . $tag . '&contentid=' . $content_id . '&page=' . $page . '">' . $page_current . '</a></li>';

/** 前一页标签*/
if ($page_before > 0) {
//    $page_before_html_str = '<a href="content_get.php?tag=' . $tag . '&contentid=' . $content_id . '&page=' . $page_before . '"><span>&nbsp前一页&nbsp</span></a>';
    $page_before_html_str = '<li class="page-item"><a class="page-link" href="/php/forum/content_get.php?tag=' . $tag . '&contentid=' . $content_id . '&page=' . $page_before . '">前一页</a></li>';

} else {
    /** 第一页隐藏 上一页*/
    $page_before_html_str = '';
}
/** 后一页标签*/
if ($page_after >= $page_max) {
    /** 最后页隐藏 下一页*/
    $page_after_html_str = '';
} else {
//    $page_after_html_str = '<a href="content_get.php?tag=' . $tag . '&contentid=' . $content_id . '&page=' . $page_after . '"><span>&nbsp后一页&nbsp</span></a>';
    $page_after_html_str = '<li class="page-item"><a class="page-link" href="/php/forum/content_get.php?tag=' . $tag . '&contentid=' . $content_id . '&page=' . $page_after . '">后一页</a></li>';

}

if ($total_count > 0) {
    /** 通过_id倒叙排序，显示最新评论，limit和skip控制显示条数和分页功能*/
    $command_arr = [
        "find" => $col_name,
        // 倒序显示评论，通过 _id倒叙排列
        'sort' => ['_id' => -1],
        // 显示数量控制
        'limit' => $count_per_page,
        // 分页使用
        'skip' => $count_per_page * $page
    ];
    $command = new MongoDB\Driver\Command($command_arr);
    $cursor = $manager->executeCommand($db_name, $command);
    //TODO 笔记数据分页
    /**
     * <li style="display: flex">
     * <div>
     * <img src="" width="50px" height="50px">
     * <p class="text-center">
     * 名字
     * </p>
     * </div>
     * <div>
     * <div style="text-align: right">
     * 2018-08-08
     * </div>
     * <div>
     * 这里是笔记内容
     * </div>
     * </div>
     * </li>
     */
    $note_list_content = '';
    foreach ($cursor as $doc) {

        $content = $doc->content;
        $editor_id = $doc->editorid;
        $editor_name = $doc->editorname;
        $edit_time = $doc->edittime;

        $note_list_content .= '<li style="display: flex">'
            . '<div>'
            . '<img class="img-responsive center-block" src="../../head_img/' . $editor_id . '.jpg" width="50px" height="50px">'
            . '<p class="text-center">'
            . $editor_name
            . '</p>'
            . '</div>'
            . '<div style="width: 100%">'
            . '<div class="text-right">'
            . date("Y-m-d H:i:s", $edit_time)
            . '</div>'
            . '<div>'
            . $content
            . '</div>'
            . '</div>'
            . '</li>';

    }

    echo '<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="../../lib/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <script src="../../lib/google-code-prettify/run_prettify.js"></script>
    <link rel="stylesheet" href="../../css/dashidan.css">
</head>
<body>
' . $note_list_content . '

<ul class="pagination">
<li class="page-item"><a class="page-link" href="/php/forum/content_get.php?tag=' . $tag . '&contentid=' . $content_id . '">&nbsp首页&nbsp</a></li>
' . $page_before_html_str . '
' . $page_current_str . '
' . $page_after_html_str . '
</ul>

</body>
</html>';
} else {
    echo '<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
</head>
<body>
该文章尚没有笔记。点击编辑添加。
</body>
</html>';
}
