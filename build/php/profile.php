<?php
session_start();
require_once('util_level.php');

if (!isset($_SESSION['user_id'])) {
    echo "请先登录 user_id";
    return;
}

$user_id = intval($_SESSION['user_id']);

$res = new stdClass();

if ($user_id) {
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter = ['user_id' => $user_id];
    $options = ['limit' => 1];
    $query_find = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery('db_account.col_user', $query_find);
    $user_info = $cursor->toArray()[0];
    /** 用户显示信息*/
    $nick_name = $user_info->nickname;
    $exp = $user_info->exp;
    $create_time = $user_info->create_time;
    /** 计算等级和最大经验*/
    $level = getLevelByExp($exp);
    $exp_max = getLevelExpMax($level);
    $percent = intval($exp * 100 / $exp_max);

    $res->state = 0;
    $res->id = $user_id;
    $res->name = $user_info->nickname;
    $res->exp = $user_info->exp;
    $res->create_time = date('Y-m-d', $user_info->create_time);
    $res->level = $user_info->level;
    $res->exp_max = $exp_max;
    $res->percent = $percent;
} else {
    $res->state = -1;
}

echo json_encode($res);
?>

<!--<form>-->
<!--    <div class="form-group row">-->
<!--        <label for="staticEmail" class="col-form-label" style="width: 80px;">昵称:</label>-->
<!--        <div>-->
<!--            <input type="text" readonly class="form-control-plaintext" id="staticEmail"-->
<!--                   value="--><?php //echo $nick_name ?><!--">-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="form-group row">-->
<!--        <label for="staticEmail" class="col-form-label" style="width: 80px;">ID:</label>-->
<!--        <div>-->
<!--            <input type="text" readonly class="form-control-plaintext" id="staticEmail"-->
<!--                   value="--><?php //echo $user_id ?><!--">-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="form-group row">-->
<!--        <label for="staticEmail" class="col-form-label" style="width: 80px;">注册:</label>-->
<!--        <div>-->
<!--            <input type="text" readonly class="form-control-plaintext" id="staticEmail"-->
<!--                   value="--><?php //echo date('Y-m-d', $create_time) ?><!--">-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="form-group row">-->
<!--        <label for="inputPassword" class="col-form-label" style="width: 80px;">等级:</label>-->
<!--        <div>-->
<!--            <input type="text" readonly class="form-control-plaintext" id="inputPassword"-->
<!--                   value="--><?php //echo $level ?><!--">-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="form-group row">-->
<!--        <label for="inputExp" class="col-form-label" style="width: 80px;">经验:</label>-->
<!--        <div>-->
<!--            <input type="text" readonly class="form-control-plaintext" id="inputExp"-->
<!--                   value="--><?php //echo $exp . '/' . $exp_max; ?><!--">-->
<!--        </div>-->
<!--    </div>-->
<!--</form>-->
<!--<div class="progress">-->
<!--    <div class="progress-bar progress-bar-striped bg-success" role="progressbar"-->
<!--         style="width: --><?php //echo $percent ?>/*%" aria-valuenow="*/<?php //echo $exp ?><!--" aria-valuemin="0"-->
<!--         aria-valuemax="--><?php //echo $exp_max ?><!--">-->
<!--    </div>-->
<!--</div>-->