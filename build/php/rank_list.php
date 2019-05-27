<?php
session_start();
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/3/27
 * Time: 10:02
 */

require_once('util_level.php');
require_once('util_time.php');

$TYPE_TODAY = 'rank_1';
$TYPE_YESTERDAY = 'rank_2';
$TYPE_WEEK = 'rank_3';
$TYPE_WEEK_LAST = 'rank_4';
$TYPE_MONTH = 'rank_5';
$TYPE_MONTH_LAST = 'rank_6';
$TYPE_ALL = 'rank_7';

/** 排行榜类型 默认今日*/
$type = $TYPE_TODAY;
if (isset($_GET['type'])) {
    $type = $_GET['type'];
}

$file = '/workplace/log/log_rank_list_' . $today . '.log';
if (isset($_GET['userid'])) {
    $user_id = $_GET['userid'];
    $content = ' userid ' . $user_id . ' type ' . $type . " $time_stamp\n";
} else {
    $content = ' type ' . $type . " $time_stamp\n";
}
file_put_contents($file, $content, FILE_APPEND);

/** 获取redis的排行榜数据*/
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$key = null;
$title = "排行榜";
$tip = "";
/** 排行榜按钮状态默认灰色的 secondary，选中类型为primary 蓝色*/
$btn_state = 'btn-secondary';

switch ($type) {
    case $TYPE_TODAY:
        $key = 'redis_rank_today';
        $title = "今日排行榜";
        $tip = '每分钟更新';
        break;
    case $TYPE_YESTERDAY:
        $key = 'redis_rank_yesterday';
        $title = "昨日排行榜";
        $tip = '每日0点更新';
        break;
    case $TYPE_WEEK:
        $key = 'redis_rank_week';
        $title = "本周排行榜";
        $tip = '每分钟更新';
        break;
    case $TYPE_WEEK_LAST:
        $key = 'redis_rank_week_last';
        $title = "上周排行榜";
        $tip = '每周一0点更新';
        break;
    case $TYPE_MONTH:
        $key = 'redis_rank_month';
        $title = "本月排行榜";
        $tip = '每分钟更新';
        break;
    case $TYPE_MONTH_LAST:
        $key = 'redis_rank_month_last';
        $title = "上月排行榜";
        $tip = '每月1日0点更新';
        break;
    case $TYPE_ALL:
        $key = 'redis_rank_all';
        $title = "总排行榜";
        $tip = '每分钟更新';
        break;
}

if (!$key) {
    return;
}

//if ($key) {
$res = $redis->get($key);
$rank_list = json_decode($res);
//if ($rank_list && sizeof($rank_list) > 0) {
/**
 * <tr>
 * <th scope="row">1</th>
 * <td>头像</td>
 * <td>Sinbad</td>
 * <td>95</td>
 * </tr>
 */
//    if ($type == $TYPE_ALL) {
//            /** 总榜显示等级*/
//            $note_list_content = '<table class="table">
//<thead>
//        <tr class="table-active">
//            <th>排名</th>
//            <th>头像</th>
//            <th>昵称</th>
//            <th>等级</th>
//            <th>经验</th>
//        </tr>
//        </thead>
//        <tbody>';
//        } else {
//            /** 其他榜不显示等级*/
//            $note_list_content = '<table class="table">
//<thead>
//        <tr class="table-active">
//            <th>排名</th>
//            <th>头像</th>
//            <th>昵称</th>
//            <th>经验</th>
//        </tr>
//        </thead>
//        <tbody>';
//        }
//    $rank = 1;
//    foreach ($rank_list as $value) {
//        $headimgurl = str_replace('http://', 'https://', $value->headimgurl);
//        $nick_name = $value->nickname;
//        $exp = $value->exp;
//        $level = getLevelByExp($exp);
//
////            /** 前三名的格式特殊处理*/
////            switch ($rank) {
////                case 1:
////                    $note_list_content .= '<tr class="table-danger">';
////                    break;
////                case 2:
////                    $note_list_content .= '<tr class="table-warning">';
////                    break;
////                case 3:
////                    $note_list_content .= '<tr class="table-success">';
////                    break;
////                default:
////                    $note_list_content .= '<tr>';
////                    break;
////            }
//
//        if ($type == $TYPE_ALL) {
//            /** 总榜显示等级*/
//            $note_list_content .= '<th valign="middle">' . $rank . '</th>'
//                . '<td>'
//                . '<img class="img-responsive center-block" src="' . $headimgurl . '" width="50px" height="50px">'
//                . '</td>'
//                . '<td valign="middle">' . $nick_name . '</td>'
//                . '<td valign="middle">' . $level . '</td>'
//                . '<td valign="middle">' . $exp . '</td>'
//                . '</tr>';
//        } else {
//            /** 其他榜不显示等级*/
//            $note_list_content .= '<th valign="middle">' . $rank . '</th>'
//                . '<td valign="middle">'
//                . '<img class="img-responsive center-block" src="' . $headimgurl . '" width="50px" height="50px">'
//                . '</td>'
//                . '<td valign="middle">' . $nick_name . '</td>'
//                . '<td valign="middle">' . $exp . '</td>'
//                . '</tr>';
//        }
//
//        $rank++;
//    }
//    $note_list_content .= '</tbody></table>';
//    } else {
//        $note_list_content = "<p>到排行榜刷新时间后会出数据,敬请期待.</p>";
//    }
//}
?>

<!--<div class="btn-group">-->
<!--    <button id="rank_1" class="btn btn-light active">今日</button>-->
<!--    <button id="rank_2" class="btn btn-light">昨日</button>-->
<!--    <button id="rank_3" class="btn btn-light">本周</button>-->
<!--    <button id="rank_4" class="btn btn-light">上周</button>-->
<!--    <button id="rank_5" class="btn btn-light">本月</button>-->
<!--    <button id="rank_6" class="btn btn-light">上月</button>-->
<!--    <button id="rank_7" class="btn btn-light">总榜</button>-->
<!--</div>-->
<p>
    <?php echo $title . '  ' . $tip; ?>
</p>

<!--<div>-->
<!--    --><?php //echo $note_list_content; ?>
<!--</div>-->

<table class="table">
    <thead>
    <tr class="table-active">
        <th>排名</th>
        <th>头像</th>
        <th>昵称</th>
        <th>等级</th>
        <th>经验</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($rank_list) {
        $str = '';
        $rank = 1;
        foreach ($rank_list as $value) {
            $headimgurl = str_replace('http://', 'https://', $value->headimgurl);
            $nick_name = $value->nickname;
            $exp = $value->exp;
            $level = getLevelByExp($exp);
            $str .= '    <tr><th valign="middle">' . $rank . '</th>'
                . '<td><img class="img-responsive center-block" src="' . $headimgurl . '" width="50px" height="50px"></td>'
                . '<td valign="middle">' . $nick_name . '</td>'
                . '<td valign="middle">' . $level . '</td>'
                . '<td valign="middle">' . $exp . '</td>'
                . '</tr>';
            $rank++;
        }
        echo $str;
    }
    ?>

    </tbody>
</table>