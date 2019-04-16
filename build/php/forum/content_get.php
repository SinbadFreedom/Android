<?php
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/3/15
 * Time: 12:20
 */
error_reporting(E_ALL ^ E_NOTICE);

if (!isset($_GET['num'])) {
    echo 'param error 3';
    return;
}

if (!is_numeric($_GET['num'])) {
    echo 'param error 4';
    return;
}

if (!isset($_GET['tag'])) {
    echo 'param error 5';
    return;
}

$tag = $_GET['tag'];
$num = $_GET['num'];

$time_stamp = time();
$file = '../../log/log_content_get_' . date('Y-m-d', $time_stamp) . '.txt';
$content = "$tag " . " $num" . " $time_stamp\n";;
file_put_contents($file, $content, FILE_APPEND);

/** collection name*/;
$db_name = 'db_content';
$col_name = $tag . '_' . $num;
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

$query = array(
    "count" => $col_name,
    "query" => [],
);
$command = new MongoDB\Driver\Command($query);
$command_cursor = $manager->executeCommand($db_name, $command);
/** 笔记总条数*/
$noteCount = $command_cursor->toArray()[0]->n;

if ($noteCount > 0) {
    $query = new MongoDB\Driver\Query([]);
    $cursor = $manager->executeQuery($db_name . '.' . $col_name, $query);
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

        $note = $doc->content;
//        $open_id = $doc->openid;
        $user_id = $doc->userid;
        $nick_name = $doc->name;
        $time = $doc->time;

        $note_list_content .= '<li style="display: flex">'
            . '<div>'
            . '<img class="img-responsive center-block" src="../../head_img/' . $user_id . '.jpg" width="50px" height="50px">'
            . '<p class="text-center">'
            . $nick_name
            . '</p>'
            . '</div>'
            . '<div style="width: 100%">'
            . '<div class="text-right">'
            . date("Y-m-d H:i:s", $time)
            . '</div>'
            . '<div>'
            . $note
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
