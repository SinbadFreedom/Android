<?php
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/3/15
 * Time: 12:20
 */

use MongoDB\Driver\Query;

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

$tag = $_GET['tag'];
$content_id = $_GET['contentid'];

//$time_stamp = time();
$file = '/workplace/log/log_note_get_' . date('Y-m-d', $time_stamp) . '.log';
$content = $tag . " $content_id" . " $time_stamp\n";
file_put_contents($file, $content, FILE_APPEND);

/** collection name*/;
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$col_name = 'db_tag.'. $tag;
$filter = ['contentid' => $content_id];
$options = array(
    'limit' => 1
);
/** 根据tag和content_id查找对应的文章标题信息*/
$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery($col_name, $query);
$info = $cursor->toArray()[0];

$content_id = $info->contentid;
$title = $info->title;
$author_id = $info->authorid;
$author_name = $info->authorname;
$create_time = $info->createtime;
$comment_count = $info->commentcount;
$create_date = date("md H:i", $create_time);
/** 最后编辑用户的信息 初始为空*/
$editor_id = $info->editorid;
$editor_name = $info->editorname;
$edit_time = $info->edittime;

$filter = ['contentid' => $content_id];
$options = array(
    'limit' => 1
);

/** 文章内容信息*/
$query = new MongoDB\Driver\Query($filter, $options);
$col_content_name = 'db_content.'. $tag;
$cursor = $manager->executeQuery($col_content_name, $query);
$content_info = $cursor->toArray()[0];
$content = $content_info->content;
$author_figure = $content_info->author_figure;
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>熊猫文档-面向程序员的技术文档网站</title>
    <link rel="stylesheet" href="/lib/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <script src="/lib/google-code-prettify/run_prettify.js"></script>
    <link rel="stylesheet" href="/css/dashidan.css">
</head>
<body>

<div style="background: #2196F3">
    <img src="/img/web_1.png">
</div>

<nav class="navbar navbar-expand navbar-light">
    <div class="container">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/index.php"><b>首页</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="/php/forum/index.php"><b>笔记</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/php/rank_list.php"><b>排行榜</b></a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <?php
                if (isset($_SESSION['figureurl_qq'])) {
                    echo '<a class="nav-link" href="/php/user_info.php"><img src="' . $_SESSION['figureurl_qq'] . '" width="24px" height="24px"></a>';
                } else {
                    echo '<a class="nav-link" href="/php/login.php"><b>登录</b></a>';
                }
                ?>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <div>
        <a href="/index.php">&nbsp首页&nbsp</a>/<a href="/php/forum/index.php">&nbsp笔记&nbsp</a>/ <?php echo $tag ?> / <?php echo $title ?>
    </div>

    <div class="row">
        <div class="ml-auto">
            <button class="btn btn-success ml-auto" type="button">发表回复</button>
        </div>
    </div>

    <table>
        <tbody>
        <tr>
            <td width="48px">
                <img src="<?php echo $author_figure ?>" width="48px" height="48px">
                <span><?php echo $author_name ?></span>
            </td>
            <td width="auto" valign="top">
                <div>
                    <span><b><?php echo $title ?></b></span>
                    <span><small><?php echo $create_time ?></small></span>
                </div>
                <div>
                    <span><?php echo $content ?></span>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>