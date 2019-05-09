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
}

$tag = $_GET['tag'];
$content_id = intval($_GET['contentid']);

$file = '/workplace/log/log_note_get_' . date('Y-m-d', $time_stamp) . '.log';
$content = $tag . " $content_id" . " $time_stamp\n";
file_put_contents($file, $content, FILE_APPEND);/** collection name*/;
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$col_tag_name = 'db_tag.' . $tag;
$filter = ['contentid' => $content_id];
/** 不返回文章内容*/
$options = ['limit' => 1];
/** 根据tag和content_id查找对应的文章标题信息*/
$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery($col_tag_name, $query);
$info = $cursor->toArray()[0];

$author_id = $info->authorid;
$author_name = $info->authorname;
$author_figure = $info->author_figure;
$create_time = $info->createtime;
$title = $info->title;
$content = $info->content;

/** 笔记翻页*/
$count_per_page = 20;

$command = new MongoDB\Driver\Command([
        /** $tag 是 collection name */
    'count' => $tag,
    'query' => ['content_id' => $content_id]
]);

$command_cursor = $manager->executeCommand('db_reply', $command);
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
/** 页数*/
$page_current_str = '<li class="page-item"><a class="page-link" href="/php/forum/note_get.php?tag=' . $tag . '&contentid=' . $content_id . '&page=' . $page . '">' . ($page + 1) . '</a></li>';
/** 前一页标签*/
if ($page > 0) {
    $page_before_html_str = '<li class="page-item"><a class="page-link" href="/php/forum/note_get.php?tag=' . $tag . '&contentid=' . $content_id . '&page=' . ($page - 1) . '">前一页</a></li>';
} else {
    /** 第一页隐藏 上一页*/
    $page_before_html_str = '';
}
/** 后一页标签*/
if ($page < $page_max) {
    $page_after_html_str = '<li class="page-item"><a class="page-link" href="/php/forum/note_get.php?tag=' . $tag . '&contentid=' . $content_id . '&page=' . ($page + 1) . '">后一页</a></li>';
} else {
    /** 最后页隐藏 下一页*/
    $page_after_html_str = '';
}

$filter = ['content_id' => $content_id];
/** 只返回标题相关内容，不返回文章内容*/
$options = [
    /** 按时间倒叙排列*/
    'sort' => ['_id' => -1],
    // 显示数量控制
    'limit' => $count_per_page,
    // 分页使用
    'skip' => $count_per_page * $page
    ];
/** 根据tag和content_id查找对应的文章标题信息*/
$query = new MongoDB\Driver\Query($filter, $options);
$col_reply_name = 'db_reply.' . $tag;
$cursor = $manager->executeQuery($col_reply_name, $query);

$reply_html_str = '';
foreach ($cursor as $document) {
    /**
     * 回复数据
     * 'editor_id' => $user_id,
     * 'editor_name' => $nick_name,
     * 'editor_figure' => $editor_figure,
     * 'reply' => $reply,
     * 'edit_time' => $time_stamp,
     */
    $document->editor_id;
    $editor_name = $document->editor_name;
    $editor_figure = $document->editor_figure;
    $edit_time = $document->edit_time;
    $reply = $document->reply;

    /**
     * 组成html字符串
     */
    $reply_html_str .= '<tr>
            <td width="48px">
                <img src="' . $editor_figure . '" width="48px" height="48px">
                <span>' . $editor_name . '</span>
            </td>
            <td width="auto" valign="top">
                <div class="row">
                    <span class="ml-auto"><small>' . date("m-d H:i", $edit_time) . '</small></span>
                </div>
                <div>
                    <span>' . $reply . '</span>
                </div>
            </td>
        </tr>';
}
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
                    echo '<a class="nav-link" href="/php/login_ui.php"><b>登录</b></a>';
                }
                ?>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <div>
        <a href="/index.php">&nbsp首页&nbsp</a>/<a href="/php/forum/index.php">&nbsp笔记&nbsp</a>/ <a
                href="/php/forum/index.php?tag=<?php echo $tag ?>">&nbsp<?php echo $tag ?>&nbsp</a>
        / <?php echo $title ?>
    </div>

    <table>
        <tbody>
        <tr>
            <td width="48px">
                <img src="<?php echo $author_figure ?>" width="48px" height="48px">
                <span><?php echo $author_name ?></span>
            </td>
            <td width="100%" valign="top">
                <div class="row">
                    <span><b><?php echo $title ?></b></span>
                    <span class="ml-auto"><small><?php echo date("m-d H:i", $create_time) ?></small></span>
                </div>
                <div>
                    <span><?php echo $content ?></span>
                </div>
            </td>
        </tr>
        <!-- 回复内容-->
        <?php echo $reply_html_str ?>
        </tbody>
    </table>

    <ul class="pagination">
        <li class="page-item"><a class="page-link" href="/php/forum/note_get.php?tag=<?php echo $tag ?>&contentid=<?php echo $content_id ?>">&nbsp首页&nbsp</a></li>
        <?php echo $page_before_html_str ?>
        <?php echo $page_current_str ?>
        <?php echo $page_after_html_str ?>
    </ul>
</div>

<div class="container">
    <form action="需要js替换的reply URL" method="post" id="note_reply">
        <div class="form-group">
            <label for="content_reply">发表回复</label>
            <textarea class="form-control" id="reply" name="reply" rows="5" placeholder="请输入回复内容"></textarea>
        </div>
        <?php
        if (isset($_SESSION['figureurl_qq'])) {
            echo '<button type="submit" class="btn btn-primary ml-auto">发表回复</button>';
        } else {
            echo '<label class="btn btn-warning ml-auto">登录后方可回复</label>';
        }
        ?>
    </form>
</div>

<script src="/lib/jquery-3.2.1.min.js"></script>

<script>
    function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return decodeURI(r[2]);
        return null;
    }

    var tag = getUrlParam('tag');
    var content_id = getUrlParam('contentid');

    var reply_rul = 'note_reply_summit.php?tag=' + tag + '&contentid=' + content_id;
    /** 替换 reply 的url*/
    $('#note_reply').attr('action', reply_rul);
</script>
<script>
    $("#note_reply").submit(function () {
        var reply = $("#reply").val();
        /** 这里是提交表单前的非空校验*/
        if (reply === "" || !reply) {
            alert("请输入回复内容");
            return false;/*阻止表单提交*/
        }
        return true;
    });
</script>
</body>
</html>