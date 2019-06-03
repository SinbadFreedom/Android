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

if (!isset($_GET['language'])) {
    echo 'param error language';
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
$language = $_GET['language'];
$content_id = intval($_GET['contentid']);

$file = '/workplace/log/log_note_get_' . date('Y-m-d', $time_stamp) . '.log';
$content = $tag . $language . " $content_id " . " $time_stamp\n";
file_put_contents($file, $content, FILE_APPEND);
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');

$note_collection_name = $tag . '_' . $language;

/** 笔记翻页*/
$count_per_page = 10;

$command = new MongoDB\Driver\Command([
    /** $tag 是 collection name */
    'count' => $note_collection_name,
    'query' => ['content_id' => $content_id]
]);

$command_cursor = $manager->executeCommand('db_note', $command);
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
    /** 显示数量控制 */
    'limit' => $count_per_page,
    /** 分页使用 */
    'skip' => $count_per_page * $page
];
/** 根据tag和content_id查找对应的文章标题信息*/
$query = new MongoDB\Driver\Query($filter, $options);
$col_reply_name = 'db_note.' . $note_collection_name;
$cursor = $manager->executeQuery($col_reply_name, $query);

$reply_info = [];
foreach ($cursor as $document) {
    /**
     * 回复数据
     * 'editor_id' => $user_id,
     * 'editor_name' => $nick_name,
     * 'editor_figure' => $editor_figure,
     * 'reply' => $reply,
     * 'edit_time' => $time_stamp,
     */
    $info = new stdClass();
    $info->editor_id = $document->editor_id;
    $info->editor_name = $document->editor_name;
    $info->editor_figure = $document->editor_figure;
    $info->edit_time = date("m-d H:i", $document->edit_time);
    $info->reply = $document->reply;

    array_push($reply_info, $info);
}

$reply_rul = '/php/forum/note_reply.php?tag=' . $tag . '&contentid=' . $content_id;

?>

<table>
    <tbody>
    <?php
    foreach ($reply_info as $info) {
        echo '<tr>
                <td width="96px">
                    <img src="' . $info->editor_figure . '" width="48px" height="48px">
                    <div class="text-center">
                        <span>' . $info->editor_name . '</span>
                    </div>
                </td>
                <td width="100%" valign="top">
                    <div class="row">
                        <span class="ml-auto"><small>' . $info->edit_time . '</small></span>
                    </div>
                    <div>
                        <span>' . $info->reply . '</span>
                    </div>
                </td>
            </tr>';
    }
    ?>
    </tbody>
</table>

<ul class="pagination">
    <li class="page-item"><a class="page-link"
                             href="/php/forum/note_get.php?tag=<?php echo $tag ?>&contentid=<?php echo $content_id ?>">&nbsp首页&nbsp</a>
    </li>
    <?php echo $page_before_html_str ?>
    <?php echo $page_current_str ?>
    <?php echo $page_after_html_str ?>
</ul>