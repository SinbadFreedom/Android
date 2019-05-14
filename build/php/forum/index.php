<?php
session_start();

error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');

/** 默认显示全部标签的文章，通过tag显示指定标签的文章*/
$tag = 'content_all';
if (isset($_GET['tag'])) {
    $tag = $_GET['tag'];
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

/** 是否显示 header区，原文档不显示，自建标题显示*/
$show_header = 1;
if (isset($_GET['show_header'])) {
    $show_header = intval($_GET['show_header']);
}

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
    /** 只返回标题相关内容，不返回文章内容*/
    $options = ['limit' => 1, 'content' => 0];
    /** 根据tag和content_id查找对应的文章标题信息*/
    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery($col_name, $query);
    $info = $cursor->toArray()[0];
    /**
     * $note_content = ['contentid' => intval($content_id), 'title' => $title, 'authorid' => $user_id, 'authorname' => $nick_name, 'createtime' => $time_stamp];
     */
    $content_id = $info->contentid;
    $title = $info->title;
    $author_id = $info->authorid;
    $author_name = $info->authorname;
    $create_time = $info->createtime;
    $comment_count = $info->comment_count;
    $create_date = date("m-d H:i", $create_time);
    /** 最后编辑用户的信息 初始为空，回复时赋值note_reply_summit.php*/
    $editor_id = $info->editor_id;
    $editor_name = $info->editor_name;
    $edit_time = $info->edit_time;

    $time_diff_str = '';
    if ($edit_time) {
        $diff = time() - $edit_time;
        $time_diff_str = time2Units($diff) . '前';
    }

    /** 文章url 跳到中文文章的评论区*/
    $content_url = '/php/forum/note_get.php?tag=' . $tag . '&contentid=' . $content_id;
    $tag_url = '/php/forum/index.php?tag=' . $tag;
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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/lib/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <script src="/lib/google-code-prettify/run_prettify.js"></script>
    <link rel="stylesheet" href="/css/dashidan.css">
</head>
<body>
<?php
if ($show_header == 1) {
    echo '<div style="background: #2196F3">
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
            <li class="nav-item">';
    if (isset($_SESSION['figure_url'])) {
        echo '<a class="nav-link" href="/php/user_info.php" ><img class="rounded" src="' . $_SESSION['figure_url'] . '" width = "24px" height = "24px" ></a > ';
    } else {
        echo '<a class="nav-link" href="/php/login_ui.php" ><b>登录</b></a>';
    }
    echo '</li>
        </ul>
    </div>
</nav>
<div class="container">
    <div>
        <a href="/php/forum/index.php"><span class="panda_border alert-primary">全部</span></a>
        <a href="/php/forum/index.php?tag=python3.7.2"><span class="panda_border alert-primary">python3.7.2</span></a>
        <a href="/php/forum/index.php?tag=技术讨论"><span class="panda_border alert-primary">技术讨论</span></a>
        <a href="/php/forum/index.php?tag=灌水乐园"><span class="panda_border alert-primary">灌水乐园</span></a>
    </div>
    <div class="row">
        <span class="btn btn-secondary">
            <?php
            if (isset($_GET[\'tag\'])) {
                echo $_GET[\'tag\'];
            } else {
                echo \'全部\';
            }
            ?>
        </span>

        <div class="dropdown ml-auto">
            <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                发表新的笔记
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="note_new_ui.php?tag=python3.7.2">python3.7.2</a>
                <a class="dropdown-item" href="note_new_ui.php?tag=技术讨论">技术讨论</a>
                <a class="dropdown-item" href="note_new_ui.php?tag=灌水乐园">灌水乐园</a>
            </div>
        </div>
    </div>
</div>';
};
?>

<div class="container">
    <table class="table">
        <?php echo $titles_str ?>
    </table>

    <ul class="pagination">
        <li class="page-item"><a class="page-link" href="index.php">&nbsp首页&nbsp</a></li>
        <?php if ($page - 1 >= 0) {
            echo '<li class="page-item"><a class="page-link" href="index.php?page=' . ($page - 1) . '">上页</a></li>';
        } else {
            /** 第一页隐藏 上一页*/
        }
        echo '<li class="page-item"><a class="page-link" href="index.php?page=' . $page . '">' . ($page + 1) . '</a></li>';

        if ($page + 1 >= $page_max) {
            /** 最后页隐藏 下一页*/
        } else {
            echo '<li class="page-item"><a class="page-link" href="index.php?page=' . ($page + 1) . '">下页</a></li>';
        }
        ?>
    </ul>
</div>

<script src="/lib/jquery-3.2.1.min.js"></script>
<script src="/lib/bootstrap-4.3.1-dist/js/popper.min.js"></script>
<script src="/lib/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
</body>
</html>