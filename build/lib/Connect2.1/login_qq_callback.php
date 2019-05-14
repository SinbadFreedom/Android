<?php
require_once("API/qqConnectAPI.php");
/** 获取 token 和 open_id */
$qc = new QC();
$access_token = $qc->qq_callback();
$open_id = $qc->get_openid();
/** 获取用户信息*/
$qc_info = new QC($access_token, $open_id);
$arr = $qc_info->get_user_info();
/**
 * B95E1C6C1D39AF7978AEBE978633382E
 * B1B2F5859B8CDE96F93531960D2D176B
 * Array ( [ret] => 0 [msg] => [is_lost] => 0 [nickname] => Sinbad [gender] => 男 [province] => 北京 [city] => [year] => 1982 [constellation] => [figureurl] => http://qzapp.qlogo.cn/qzapp/101569922/B1B2F5859B8CDE96F93531960D2D176B/30 [figureurl_1] => http://qzapp.qlogo.cn/qzapp/101569922/B1B2F5859B8CDE96F93531960D2D176B/50 [figureurl_2] => http://qzapp.qlogo.cn/qzapp/101569922/B1B2F5859B8CDE96F93531960D2D176B/100 [figureurl_qq_1] => http://thirdqq.qlogo.cn/g?b=oidb&k=mwBhEnLgHCziaHdoTau4KWw&s=40 [figureurl_qq_2] => http://thirdqq.qlogo.cn/g?b=oidb&k=mwBhEnLgHCziaHdoTau4KWw&s=100 [figureurl_qq] => http://thirdqq.qlogo.cn/g?b=oidb&k=mwBhEnLgHCziaHdoTau4KWw&s=140 [figureurl_type] => 1 [is_yellow_vip] => 0 [vip] => 0 [yellow_vip_level] => 0 [level] => 0 [is_yellow_year_vip] => 0 )
 *
 * $arr[nickname];
 * $arr[gender];
 * $arr[province];
 * $arr[city];
 * $arr[year];
 * $arr[figureurl_qq];
 * $arr[figureurl_type];
 */

/** 数据入库，返回user_id*/
$headimgurl = $arr['figureurl_qq'];
$nickname = $arr['nickname'];
$sex = $arr['gender'];
$province = $arr['province'];
$city = $arr['city'];
$year = $arr['year'];
$figureurl_type = $arr['figureurl_type'];
$time_stamp = time();

$user_id = -1;
$exp = 1;
$is_new = false;
/** 根据openid 查找用户数据*/
$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
$filter = ['openid' => $open_id];
$options = array(
    'limit' => 1
);
$query_find = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('db_account.col_user', $query_find);
$user_info = $cursor->toArray()[0];
/** 区分新老用户*/
if ($user_info) {
    /** 老用户*/
    $user_id = $user_info->user_id;
    $exp = $user_info->exp;
} else {
    /** 新用户*/
    $is_new = true;
    /** 生成自增id*/
    $query = array(
        "findandmodify" => "col_increase",
        "query" => ['table' => 'inc_user_id'],
        "update" => ['$inc' => ['user_id_now' => 1]],
        'upsert' => true,
        'new' => true,
        'fields' => ['user_id_now' => 1]
    );
    $command = new MongoDB\Driver\Command($query);
    $command_cursor = $manager->executeCommand('db_account', $command);
    $response = $command_cursor->toArray()[0];
    /** 获取新用户id*/
    $user_id = $response->value->user_id_now;
    /** 插入用户表*/
    $bulkInsertUser = new MongoDB\Driver\BulkWrite();
    $bulkInsertUser->insert([
        'openid' => $open_id,
        'access_token' => $access_token,

        'headimgurl' => $headimgurl,
        'nickname' => $nickname,
        'sex' => $sex,
        'province' => $province,
        'city' => $city,

        'user_id' => $user_id,
        'exp' => $exp,
        'exp_time' => $time_stamp,
        'create_time' => $time_stamp
    ]);
    /** 插入数据库*/
    $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 3000);
    $insertOneResult = $manager->executeBulkWrite('db_account.col_user', $bulkInsertUser, $writeConcern);
}

/** 初始化$_SESSION 数据*/
$_SESSION['figure_url'] = $arr['figureurl_qq'];
$_SESSION['nickname'] = $arr['nickname'];
$_SESSION['user_id'] = $user_id;

/** login_ui.php中记录来路url，完成登陆，跳转回去*/
$from_url = $_SESSION['from_url'];
header("Location: $from_url");
return;
