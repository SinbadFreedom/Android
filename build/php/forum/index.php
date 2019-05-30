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
/** 文档tag集合，文档笔记的url特殊处理，指向文档url的笔记区*/
$doc_tag_arr = ['python3.7.2'];
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
//$note_list = '';
//foreach ($res as $key => $value) {
//    /** $key 格式 Python3.7.2_2 $tag_$content_id*/
//    $pos = strrpos($key, '_');
//    $tag = substr($key, 0, $pos);
//    $content_id_str = substr($key, $pos + 1);
//    $content_id = intval($content_id_str);
//    $col_name = 'db_tag.' . $tag;
//    $filter = ['contentid' => $content_id];
//    /** 只返回标题相关内容，不返回文章内容*/
//    $options = ['limit' => 1, 'content' => 0];
//    /** 根据tag和content_id查找对应的文章标题信息*/
//    $query = new MongoDB\Driver\Query($filter, $options);
//    $cursor = $manager->executeQuery($col_name, $query);
//    $info = $cursor->toArray()[0];
//    /**
//     * $note_content = ['contentid' => intval($content_id), 'title' => $title, 'authorid' => $user_id, 'authorname' => $nick_name, 'createtime' => $time_stamp];
//     */
//    $content_id = $info->contentid;
//    $title = $info->title;
//    $author_id = $info->authorid;
//    $author_name = $info->authorname;
//    $create_time = $info->createtime;
//    $comment_count = $info->comment_count;
//    $create_date = date("m-d H:i", $create_time);
//    /** 最后编辑用户的信息 初始为空，回复时赋值note_reply_summit.php*/
//    $editor_id = $info->editor_id;
//    $editor_name = $info->editor_name;
//    $edit_time = $info->edit_time;
//
//    $time_diff_str = '';
//    if ($edit_time) {
//        $diff = time() - $edit_time;
//        $time_diff_str = time2Units($diff) . '前';
//    }
//
//    /** 判断tag是为文档tag*/
//    $is_doc_tag = in_array($tag, $doc_tag_arr);
//    if ($is_doc_tag) {
//        /**
//         * 系统文档笔记url示例 统一采用中文笔记url:
//         * http://panda-doc.com/python3.7.2/zh_cn/1.php#note_area
//         */
//        $content_url = '/' . $tag . '/zh_cn/' . $content_id . '.php#note_area';
//    } else {
//        /**
//         * 自建笔记url示例
//         * http://panda-doc.com/php/forum/note_get.php?tag=%E7%81%8C%E6%B0%B4%E4%B9%90%E5%9B%AD&contentid=100038
//         */
//        $content_url = '/php/forum/note_get.php?tag=' . $tag . '&contentid=' . $content_id;
//    }
//    $tag_url = '/php/forum/index.php?tag=' . $tag;
//    /**
//     * 单个标题格式
//     * <tr>
//     * <td class="text-center" width="50px" style="vertical-align: middle; font-weight: initial">
//     * <b>93999</b>
//     * </td>
//     * <td>
//     * <div style="font-size: 18px">
//     * <a href="">3. Python的非正式简介</a>
//     * </div>
//     *
//     * <div class="d-lg-none" style="font-size: 14px">
//     * <div>
//     * <a href="">[python3.7.2]</a>
//     * <span>&nbsp•&nbsp</span> 系统 <span>&nbsp0422 09:50&nbsp</span> Sinbad<span>&nbsp5天前</span>
//     * </div>
//     * </div>
//     * </td>
//     * <td class="d-none d-lg-block text-right">
//     * <a href="">[python3.7.2]</a><span>&nbsp&nbsp•&nbsp&nbsp</span>系统<span>&nbsp&nbsp0422 09:50&nbsp&nbsp</span>Sinbad<span>&nbsp5天前</span>
//     * </td>
//     * </tr>
//     */
//    $note_list .= '<tr>
//            <td class="text-center" width="30px" style="vertical-align: middle; font-weight: initial; font-size: 14px">
//            <b>' . $comment_count . '</b>
//        </td>
//        <td>
//            <div style="font-size: 18px">
//                <a href="' . $content_url . '">' . $title . '</a>
//                    <div class="d-lg-none" style="font-size: 14px">
//    <div>
//    <a href="' . $tag_url . '">[' . $tag . ']</a>&nbsp' . $author_name . '&nbsp<span>' . $create_date . '</span><span>&nbsp' . $editor_name . '</span>&nbsp' . $time_diff_str . '
//    </div>
//            </div>
//        </td>
//        <td class="d-none d-lg-block text-right">
//    <a href="' . $tag_url . '">[' . $tag . ']</a>&nbsp' . $author_name . '&nbsp<span>' . $create_date . '</span><span>&nbsp' . $editor_name . '&nbsp</span>' . $time_diff_str . '</td></tr>';
//}

//$note_list .= '</tbody>';

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

<table class="table">
    <tbody>
    <?php
    $note_list = '';
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

        /** 判断tag是为文档tag*/
        $is_doc_tag = in_array($tag, $doc_tag_arr);
        if ($is_doc_tag) {
            /**
             * 系统文档笔记url示例 统一采用中文笔记url:
             * http://panda-doc.com/python3.7.2/zh_cn/1.php#note_area
             */
            $content_url = '/doc/' . $tag . '/zh_cn/' . $content_id . '.php#note_area';
        } else {
            /**
             * 自建笔记url示例
             * http://panda-doc.com/php/forum/note_get.php?tag=%E7%81%8C%E6%B0%B4%E4%B9%90%E5%9B%AD&contentid=100038
             */
            $content_url = '/php/forum/note_get.php?tag=' . $tag . '&contentid=' . $content_id;
        }
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
        $note_list .= '<tr>'
            . '<td class="text-center" width="30px" style="vertical-align: middle; font-weight: initial; font-size: 14px"><b>' . $comment_count . '</b></td>'
            . '<td>'
            . '<div style="font-size: 18px">'
            . '<a href="' . $content_url . '">' . $title . '</a>'
            . '<div class="d-lg-none" style="font-size: 14px"><div>'
            . '<a href="' . $tag_url . '">[' . $tag . ']</a>&nbsp' . $author_name . '&nbsp<span>' . $create_date . '</span><span>&nbsp' . $editor_name . '</span>&nbsp' . $time_diff_str . '</div>'
            . '</div>'
            . '</td>'
            . '<td class="d-none d-lg-block text-right"><a href="' . $tag_url . '">[' . $tag . ']</a>&nbsp' . $author_name . '&nbsp<span>' . $create_date . '</span><span>&nbsp' . $editor_name . '&nbsp</span>' . $time_diff_str . '</td>'
            . '</tr>';

        echo $note_list;
    }
    ?>
    </tbody>
</table>