<?php
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');

/** 默认显示全部标签的文章，通过content_tag显示指定标签的文章*/
$content_tag = 'content_all';
if (isset($_GET['content_tag'])) {
    $content_tag = $_GET['content_tag'];
}

/** 论坛首页 默认按最后编辑时间读取最新20篇 编辑时间降序排序*/

$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

/** 获取总数 文章列表分页用*/
$total_count = $redis->zcard($content_tag);
/** 获取指定区间，分页用*/
$res = $redis->zrevrange($content_tag, 0, 99, true);

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
    /** 文章url*/
    $content_url = 'content_get.php?tag=' . $tag . '&contentid=' . $content_id;
    $tag_url = 'index.php?content_tag=' . $tag;
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
            <b>'. $comment_count .'</b>
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

echo '<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="../../lib/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <script src="../../lib/google-code-prettify/run_prettify.js"></script>
    <link rel="stylesheet" href="../../css/dashidan.css">
</head>
<body>

<table class="table">
' . $titles_str . '
</table>
</body>
</html>';


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