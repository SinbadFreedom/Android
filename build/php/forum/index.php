<?php
session_start();

error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');

/** 默认显示全部标签的文章，通过content_tag显示指定标签的文章*/
$content_tag = 'content_all';
if (isset($_GET['content_tag'])) {
    $content_tag = $_GET['content_tag'];
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

$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

/** 获取总数 文章列表分页用*/
$total_count = $redis->zcard($content_tag);
$page_max = intval($total_count / $count_per_page);
/** 获取指定区间，分页用*/
if ($page > $page_max) {
    /** 上边界保护*/
    $page = $page_max;
}

/** 页数*/
$page_current = $page + 1;
$page_before = $page_current - 1;
$page_after = $page_current + 1;

/** 标题列表 默认按最后编辑时间读取最新20篇 编辑时间降序排序*/
$start = $count_per_page * $page;
$res = $redis->zrevrange($content_tag, $start, $start + $count_per_page - 1, true);
/** 拼接html*/
$titles_str .= '<tbody>';
foreach ($res as $key => $value) {
    /** $key 格式 Python3.7.2_2 $tag_$content_id*/
    $pos = strrpos($key, '_');
    $tag = substr($key, 0, $pos);
    $content_id_str = substr($key, $pos + 1);
    $content_id = intval($content_id_str);
    $col_name = 'db_tag.' . $tag;
    $filter = ['contentid' => $content_id];
    $options = array(
        'limit' => 1
    );
    /** 根据tag和content_id查找对应的文章标题信息*/
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery($col_name, $query);
    $info = $cursor->toArray()[0];
    $title = $info->title;
    $author_id = $info->authorid;
    $author_name = $info->authorname;
    $create_time = $info->createtime;
    $comment_count = $info->commentcount;
    $create_date = date("md H:i");
    /** 文章url 跳到中文文章的评论区*/
    $content_url = '/' . $tag . '/zh_cn/' . $content_id . '.php#note_area';
    $tag_url = '/php/forum/index.php?content_tag=' . $tag;
    /** 最后编辑用户的信息 初始为空*/
    $editor_id = $info->editorid;
    $editor_name = $info->editorname;
    $edit_time = $info->edittime;

    $time_diff_str = '';
    if ($edit_time) {
        $diff = time() - $edit_time;
        $time_diff_str = time2Units($diff) . '前';
    }
    /**
     * 单个标题格式
     * <tr>
     * <td class="text-center" width="50px" style="vertical-align: middle; font-weight: initial">
     * <b>93999</b>
     * </td>
     * <td>
     * <div style="font-size: 18px">
     * <a href="">3. Python的非正式简介</a>
     * </div>
     *
     * <div class="d-lg-none" style="font-size: 14px">
     * <div>
     * <a href="">[python3.7.2]</a>
     * <span>&nbsp•&nbsp</span> 系统 <span>&nbsp0422 09:50&nbsp</span> Sinbad<span>&nbsp5天前</span>
     * </div>
     * </div>
     * </td>
     * <td class="d-none d-lg-block text-right">
     * <a href="">[python3.7.2]</a><span>&nbsp&nbsp•&nbsp&nbsp</span>系统<span>&nbsp&nbsp0422 09:50&nbsp&nbsp</span>Sinbad<span>&nbsp5天前</span>
     * </td>
     * </tr>
     */
    $titles_str .= '<tr>
            <td class="text-center" width="30px" style="vertical-align: middle; font-weight: initial; font-size: 14px">
            <b>' . $comment_count . '</b>
        </td>
        <td>
            <div style="font-size: 18px">
                <a href="' . $content_url . '">' . $title . '</a>
                    <div class="d-lg-none" style="font-size: 14px">
    <div>
    <a href="' . $tag_url . '">[' . $tag . ']</a>&nbsp' . $author_name . '&nbsp<span>' . $create_date . '</span><span>&nbsp' . $editor_name . '</span>&nbsp' . $time_diff_str . '
    </div>
            </div>
        </td>
        <td class="d-none d-lg-block text-right">
    <a href="' . $tag_url . '">[' . $tag . ']</a>&nbsp' . $author_name . '&nbsp<span>' . $create_date . '</span><span>&nbsp' . $editor_name . '&nbsp</span>' . $time_diff_str . '</td></tr>';
}

$titles_str .= '</tbody>';

function time2Units($time)
{
    $year = floor($time / 60 / 60 / 24 / 365);
    $time -= $year * 60 * 60 * 24 * 365;
    $month = floor($time / 60 / 60 / 24 / 30);
    $time -= $month * 60 * 60 * 24 * 30;
    $week = floor($time / 60 / 60 / 24 / 7);
    $time -= $week * 60 * 60 * 24 * 7;
    $day = floor($time / 60 / 60 / 24);
    $time -= $day * 60 * 60 * 24;
    $hour = floor($time / 60 / 60);
    $time -= $hour * 60 * 60;
    $minute = floor($time / 60);
    $time -= $minute * 60;
    $second = $time;
    $elapse = '';

    $unitArr = array('年' => 'year', '个月' => 'month', '周' => 'week', '天' => 'day',
        '小时' => 'hour', '分钟' => 'minute', '秒' => 'second'
    );

    foreach ($unitArr as $cn => $u) {
        if ($$u > 0) {
            $elapse = $$u . $cn;
            break;
        }
    }

    return $elapse;
}
?>
<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="utf-8"/>
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
            <li class="nav-item active">
                <a class="nav-link" href="/php/forum/index.php"><b>笔记</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/php/rank_list.php"><b>排行榜</b></a>
            </li>
            <?php
                if (isset($_SESSION['figureurl_qq'])) {
                    echo '<a class="nav-link" href="/php/login.php"><img src="'. $_SESSION['figureurl_qq'] .'" width="24px" height="24px"></a>';
                } else {
                    echo '<a class="nav-link" href="/php/login.php"><b>登录</b></a>';
                }
            ?>
        </ul>
    </div>
</nav>

<div class="container">

<table class="table">
 <?php echo $titles_str ?>
</table>

<ul class="pagination">
    <li class="page-item"><a class="page-link" href="index.php">&nbsp首页&nbsp</a></li>
    <?php if ($page_before > 0) {
        echo '<li class="page-item"><a class="page-link" href="index.php?page=' . $page_before . '">前一页</a></li>';
    } else {
        /** 第一页隐藏 上一页*/
    }
    echo '<li class="page-item"><a class="page-link" href="index.php?page=' . $page . '">' . $page_current . '</a></li>';

    if ($page_after >= $page_max) {
        /** 最后页隐藏 下一页*/
    } else {
        echo '<li class="page-item"><a class="page-link" href="index.php?page=' . $page_after . '">后一页</a></li>';
    }
    ?>
</ul>

</div>
</body>
</html>