<?php
session_start();

require_once('util_level.php');

$user_id = $_SESSION['user_id'];

if ($user_id) {
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter = ['user_id' => $user_id];
    $options = ['limit' => 1];
    $query_find = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery('db_account.col_user', $query_find);
    $user_info = $cursor->toArray()[0];
    /** 用户显示信息*/
    $head_img_url = $user_info->headimgurl;
    $nick_name = $user_info->nickname;
    $sex = $user_info->sex;
    $exp = $user_info->exp;
    $create_time = $user_info->create_time;
    /** 计算等级和最大经验*/
    $level = getLevelByExp($exp);
    $exp_max = getLevelExpMax($level);
    $percent = intval($exp * 100 / $exp_max);
}
?>
<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <title>熊猫文档-面向程序员的文档站</title>
    <link rel="stylesheet" href="/lib/bootstrap-4.3.1-dist/css/bootstrap.min.css">
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
                <a class="nav-link" href="/php/rank_list.php"><b>排行榜</b></a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item active">
                <?php
                if (isset($_SESSION['figureurl_qq'])) {
                    echo '<a class="nav-link" href="/php/user_info.php"><img class="rounded" src="' . $_SESSION['figureurl_qq'] . '" width="24px" height="24px"></a>';
                } else {
                    echo '<a class="nav-link " href="/php/login_ui.php"><b>登录</b></a>';
                }
                ?>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <div style="width: 140px; height: 140px">
        <img src="<?php echo $head_img_url ?>">
    </div>
    <div>
        <span>昵称:</span><span><?php echo $nick_name ?></span>
    </div>
    <div>
        <span>等级:</span><span><?php echo $level ?></span>
    </div>
    <div>
        <span>经验:</span><span><?php echo $exp . '/' . $exp_max;?></span>
    </div>
    <div class="progress">
        <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: <?php echo $percent ?>%" aria-valuenow="<?php echo $exp ?>" aria-valuemin="0" aria-valuemax="<?php echo $exp_max ?>"></div>
    </div>
</div>

</body>
</html>