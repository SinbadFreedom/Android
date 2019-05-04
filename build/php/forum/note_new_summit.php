<?php
session_start();
date_default_timezone_set('PRC');

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
    echo '请先登陆';
    return;
}

if (!isset($_SESSION['figureurl_qq'])) {
    echo '请先登陆';
    return;
}

if (!isset($_SESSION['user_id'])) {
    echo 'param error user_id';
    return;
}

$tag = $_POST['note_tag'];
$title = $_POST['title'];
$content = $_POST['content'];

$nick_name = $_SESSION['nickname'];
$user_id = $_SESSION['user_id'];

$time_stamp = time();

//TODO 插入数据前 检测collection 是否存在，不存在则不插入
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');


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
/** 内容信息*/
$bulk = new MongoDB\Driver\BulkWrite;
$note_content_info = [
    'contentid' => $content_id,
//    'title' => $title,
    'author_figure' => $_SESSION['figureurl_qq'],
    'content' => $content,
//    'authorid' => $user_id,
//    'authorname' => $nick_name,
//    'createtime' => $time_stamp,
//    '$comment_count' => 0
];
$bulk->insert($note_content_info);
/** collection name*/
$col_name = 'db_content.' . $tag;
$manager->executeBulkWrite($col_name, $bulk);

/** 标题信息*/
//$bulk->update(
//    ['contentid' => intval($content_id)],
//    ['$set' => ['contentid' => intval($content_id), 'title' => $title, 'authorid' => $user_id, 'authorname' => $nick_name, 'createtime' => $time_stamp]],
////    ['$set' => ['editorid' => $user_id, 'editorname' => $nick_name, 'edittime' => $time_stamp], '$inc' => ['commentcount' => 1]],
//    ['multi' => false, 'upsert' => true]
//);
//$note_content = ['contentid' => intval($content_id), 'title' => $title, 'editorid' => $user_id, 'editorname' => $nick_name, 'edittime' => $time_stamp];
$note_title_info = [
    'contentid' => $content_id,
    'title' => $title,
    'authorid' => $user_id,
    'authorname' => $nick_name,
    'createtime' => $time_stamp,
    'comment_count' => 0
];
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert($note_title_info);
$db_collection_name = 'db_tag.' . $tag;
$manager->executeBulkWrite($db_collection_name, $bulk);

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
                    echo '<a class="nav-link" href="/php/user_info.php"><img class="rounded" src="' . $_SESSION['figureurl_qq'] . '" width="24px" height="24px"></a>';
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
        <a href="/index.php">&nbsp首页&nbsp</a>/<a href="/php/forum/index.php">&nbsp笔记&nbsp</a>/ <?php echo $tag ?>
        / <?php echo $title ?>
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
                <img src="<?php echo $_SESSION['figureurl_qq'] ?>" width="48px" height="48px">
                <span><?php echo $nick_name ?></span>
            </td>
            <td width="auto" valign="top">
                <div>
                    <span><b><?php echo $title ?></b></span>
                    <span><small><?php echo $time_stamp ?></small></span>
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