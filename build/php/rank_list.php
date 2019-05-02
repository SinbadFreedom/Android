<?php
session_start();
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/3/27
 * Time: 10:02
 */
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

$time_stamp = time();
$today = date('Y-m-d', $time_stamp);
$file = '/workplace/log/log_rank_list_' . $today . '.log';
if (isset($_GET['userid'])) {
    $user_id = $_GET['userid'];
    $content = ' userid ' . $user_id . ' type ' . $type . " $time_stamp\n";
} else {
    $content = ' type ' . $type . " $time_stamp\n";
}
file_put_contents($file, $content, FILE_APPEND);


function getLevelByExp($exp)
{
    $exp_conf = [9,
        19,
        30,
        42,
        55,
        69,
        85,
        103,
        122,
        143,
        167,
        192,
        221,
        252,
        286,
        324,
        365,
        410,
        460,
        515,
        576,
        643,
        716,
        796,
        885,
        983,
        1090,
        1208,
        1338,
        1480,
        1637,
        1810,
        2000,
        2209,
        2439,
        2692,
        2970,
        3276,
        3613,
        3983,
        4391,
        4839,
        5332,
        5874,
        6470,
        7126,
        7848,
        8642,
        9515,
        10475,
        11532,
        12694,
        13972,
        15378,
        16925,
        18627,
        20499,
        22557,
        24822,
        27313,
        30054,
        33068,
        36384,
        40031,
        44043,
        48457,
        53311,
        58652,
        64526,
        70987,
        78095,
        85913,
        94514,
        103974,
        114381,
        125828,
        138419,
        152270,
        167506,
        184266,
        202702,
        222981,
        245288,
        269826,
        296817,
        326508,
        359168,
        395094,
        434612,
        478082,
        525899,
        578498,
        636357,
        700002,
        770011,
        847021,
        931732,
        1024914
    ];

    for ($i = sizeof($exp_conf) - 1; $i > 0; $i--) {
        if ($exp_conf[$i] <= $exp) {
            /** 等级为数组下标加1*/
            return $i + 1;
        }
    }

    return 1;
}

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
        $key = 'rank_today';
        $title = "今日排行榜";
        $tip = '每分钟更新';
        $btn_0 = '<button type="button" class="btn btn-primary" onclick="jump(1)">今日</button>';
        break;
    case TYPE_YESTERDAY:
        $key = 'rank_yesterday';
        $title = "昨日排行榜";
        $tip = '每日0点更新';
        $btn_1 = '<button type="button" class="btn btn-primary" onclick="jump(2)">昨日</button>';
        break;
    case TYPE_WEEK:
        $key = 'rank_week';
        $title = "本周排行榜";
        $tip = '每分钟更新';
        $btn_2 = '<button type="button" class="btn btn-primary" onclick="jump(3)">本周</button>';
        break;
    case TYPE_WEEK_LAST:
        $key = 'rank_week_last';
        $title = "上周排行榜";
        $tip = '每周一0点更新';
        $btn_3 = '<button type="button" class="btn btn-primary" onclick="jump(4)">上周</button>';
        break;
    case TYPE_MONTH:
        $key = 'rank_month';
        $title = "本月排行榜";
        $tip = '每分钟更新';
        $btn_4 = '<button type="button" class="btn btn-primary" onclick="jump(5)">本月</button>';
        break;
    case TYPE_MONTH_LAST:
        $key = 'rank_month_last';
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

            $open_id = $value->openid;
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
                $note_list_content .= '<th scope="row">' . $rank . '</th>'
                    . '<td>'
                    . '<img class="img-responsive center-block" src="../head_img/' . $open_id . '.jpg" width="50px" height="50px">'
                    . '</td>'
                    . '<td>' . $nick_name . '</td>'
                    . '<td>' . $level . '</td>'
                    . '<td>' . $exp . '</td>'
                    . '</tr>';
            } else {
                /** 其他榜不显示等级*/
                $note_list_content .= '<th scope="row">' . $rank . '</th>'
                    . '<td>'
                    . '<img class="img-responsive center-block" src="../head_img/' . $open_id . '.jpg" width="50px" height="50px">'
                    . '</td>'
                    . '<td>' . $nick_name . '</td>'
                    . '<td>' . $exp . '</td>'
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
    <div>
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