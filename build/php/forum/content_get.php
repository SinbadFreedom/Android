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
    echo 'param error 3';
    return;
}

if (!is_numeric($_GET['contentid'])) {
    echo 'param error 4';
    return;
}

if (!isset($_GET['tag'])) {
    echo 'param error 5';
    return;
}

$tag = $_GET['tag'];
$content_id = $_GET['contentid'];

$time_stamp = time();
$file = '../../log/log_content_get_' . date('Y-m-d', $time_stamp) . '.txt';
$content = "$tag " . " $content_id" . " $time_stamp\n";;
file_put_contents($file, $content, FILE_APPEND);

/** collection name*/;
$db_name = 'db_content';
$col_name = $tag . '_' . $content_id;
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

$query = [
    'count' => $col_name,
    'query' => [],
];
$command = new MongoDB\Driver\Command($query);
$command_cursor = $manager->executeCommand($db_name, $command);
/** 笔记总条数*/
$noteCount = $command_cursor->toArray()[0]->n;
//TODO 上面这个 $noteCount count()操作可以优化掉,只采用下边的 query功能
if ($noteCount > 0) {
    /** 通过_id倒叙排序，显示最新评论，limit和skip控制显示条数和分页功能*/
    $command_arr = [
        "find" => $col_name,
        // 倒序显示评论，通过 _id倒叙排列
        'sort' => ['_id' => -1],
        // 显示数量控制
        'limit' => 20,
        // 分页使用
        'skip' => 0
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
    $note_list_content = '<ul>';
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
    $note_list_content .= '</ul>';

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
