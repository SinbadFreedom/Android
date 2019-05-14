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

const TYPE_TODAY = 1;
const TYPE_YESTERDAY = 2;
const TYPE_WEEK = 3;
const TYPE_WEEK_LAST = 4;
const TYPE_MONTH = 5;
const TYPE_MONTH_LAST = 6;
const TYPE_ALL = 7;

/** 排行榜类型 默认今日*/
$type = TYPE_TODAY;
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

$btn_0 = '<button type="button" class="btn btn-secondary" onclick="jump(1)">今日</button>';
$btn_1 = '<button type="button" class="btn btn-secondary" onclick="jump(2)">昨日</button>';
$btn_2 = '<button type="button" class="btn btn-secondary" onclick="jump(3)">本周</button>';
$btn_3 = '<button type="button" class="btn btn-secondary" onclick="jump(4)">上周</button>';
$btn_4 = '<button type="button" class="btn btn-secondary" onclick="jump(5)">本月</button>';
$btn_5 = '<button type="button" class="btn btn-secondary" onclick="jump(6)">上月</button>';
$btn_6 = '<button type="button" class="btn btn-secondary" onclick="jump(7)">总榜</button>';

switch ($type) {
    case TYPE_TODAY:
        $key = $col_today;
        $title = "今日排行榜";
        $tip = '每分钟更新';
        $btn_0 = '<button type="button" class="btn btn-primary" onclick="jump(1)">今日</button>';
        break;
    case TYPE_YESTERDAY:
        $key = $col_yesterday;
        $title = "昨日排行榜";
        $tip = '每日0点更新';
        $btn_1 = '<button type="button" class="btn btn-primary" onclick="jump(2)">昨日</button>';
        break;
    case TYPE_WEEK:
        $key = $col_week;
        $title = "本周排行榜";
        $tip = '每分钟更新';
        $btn_2 = '<button type="button" class="btn btn-primary" onclick="jump(3)">本周</button>';
        break;
    case TYPE_WEEK_LAST:
        $key = $col_week_last;
        $title = "上周排行榜";
        $tip = '每周一0点更新';
        $btn_3 = '<button type="button" class="btn btn-primary" onclick="jump(4)">上周</button>';
        break;
    case TYPE_MONTH:
        $key = $col_month;
        $title = "本月排行榜";
        $tip = '每分钟更新';
        $btn_4 = '<button type="button" class="btn btn-primary" onclick="jump(5)">本月</button>';
        break;
    case TYPE_MONTH_LAST:
        $key = $col_month_last;
        $title = "上月排行榜";
        $tip = '每月1日0点更新';
        $btn_5 = '<button type="button" class="btn btn-primary" onclick="jump(6)">上月</button>';
        break;
    case TYPE_ALL:
        $key = 'rank_all';
        $title = "总排行榜";
        $tip = '每分钟更新';
        $btn_6 = '<button type="button" class="btn btn-primary" onclick="jump(7)">总榜</button>';
        break;
}

if ($key) {
    $res = $redis->get($key);
    $rank_list = json_decode($res);
    if ($rank_list && sizeof($rank_list) > 0) {

        /**
         * <tr>
         * <th scope="row">1</th>
         * <td>头像</td>
         * <td>Sinbad</td>
         * <td>95</td>
         * </tr>
         */
        if ($type == TYPE_ALL) {
            /** 总榜显示等级*/
            $note_list_content = '<table class="table">
<thead>
        <tr class="table-active">
            <th>排名</th>
            <th>头像</th>
            <th>昵称</th>
            <th>等级</th>
            <th>经验</th>
        </tr>
        </thead>
        <tbody>';
        } else {
            /** 其他榜不显示等级*/
            $note_list_content = '<table class="table">
<thead>
        <tr class="table-active">
            <th>排名</th>
            <th>头像</th>
            <th>昵称</th>
            <th>经验</th>
        </tr>
        </thead>
        <tbody>';
        }
        $rank = 1;
        foreach ($rank_list as $value) {
            $headimgurl = $value->headimgurl;
            $nick_name = $value->nickname;
            $exp = $value->exp;
            $level = getLevelByExp($exp);

            /** 前三名的格式特殊处理*/
            switch ($rank) {
                case 1:
                    $note_list_content .= '<tr class="table-danger">';
                    break;
                case 2:
                    $note_list_content .= '<tr class="table-warning">';
                    break;
                case 3:
                    $note_list_content .= '<tr class="table-success">';
                    break;
                default:
                    $note_list_content .= '<tr>';
                    break;
            }

            if ($type == TYPE_ALL) {
                /** 总榜显示等级*/
                $note_list_content .= '<th valign="middle">' . $rank . '</th>'
                    . '<td>'
                    . '<img class="img-responsive center-block" src="' . $headimgurl . '" width="50px" height="50px">'
                    . '</td>'
                    . '<td valign="middle">' . $nick_name . '</td>'
                    . '<td valign="middle">' . $level . '</td>'
                    . '<td valign="middle">' . $exp . '</td>'
                    . '</tr>';
            } else {
                /** 其他榜不显示等级*/
                $note_list_content .= '<th valign="middle">' . $rank . '</th>'
                    . '<td valign="middle">'
                    . '<img class="img-responsive center-block" src="' . $headimgurl . '" width="50px" height="50px">'
                    . '</td>'
                    . '<td valign="middle">' . $nick_name . '</td>'
                    . '<td valign="middle">' . $exp . '</td>'
                    . '</tr>';
            }

            $rank++;
        }
        $note_list_content .= '</tbody></table>';
    } else {
        $note_list_content = "<p>到排行榜刷新时间后会出数据,敬请期待.</p>";
    }
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
                <a class="nav-link" href="/php/forum/index.php"><b>笔记</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="/php/rank_list.php"><b>排行榜</b></a>
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
    <div class="text-center">
        <div class="btn-group">
            <?php echo $btn_0; ?>
            <?php echo $btn_1; ?>
            <?php echo $btn_2; ?>
            <?php echo $btn_3; ?>
            <?php echo $btn_4; ?>
            <?php echo $btn_5; ?>
            <?php echo $btn_6; ?>
        </div>
        <p>
            <?php echo $title. '  ' .$tip; ?>
        </p>
    </div>

    <div class="text-center">
        <?php
        echo $note_list_content;
        ?>
    </div>
</div>

<script>
    function jump(type){
        window.location.href = "rank_list.php?type=" + type;
    }
</script>
</body>
</html>