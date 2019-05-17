<?php

/** 通过openid 处理用户登陆，新用户初始化后返回id，老用户直接返回id*/
function login($union_id, $open_id, $nick_name, $head_img_url)
{
    $time_stamp = time();
    /** 根据openid 查找用户数据*/
    $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");
    $filter = ['union_id' => $union_id];
    $options = ['limit' => 1];
    $query_find = new MongoDB\Driver\Query($filter, $options);
    $cursor = $manager->executeQuery('db_account.col_user', $query_find);
    $user_info = $cursor->toArray()[0];
    /** 区分新老用户*/
    if ($user_info) {
        /** 老用户*/
        $user_id = $user_info->user_id;
    } else {
        /** 新用户*/
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
            'union_id' => $union_id,
            'openid' => $open_id,
            'headimgurl' => $head_img_url,
            'nickname' => $nick_name,
            'user_id' => $user_id,
            'exp' => 1,
            'exp_day' => 0,
            'create_time' => $time_stamp
        ]);
        /** 插入数据库*/
        $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 3000);
        $insertOneResult = $manager->executeBulkWrite('db_account.col_user', $bulkInsertUser, $writeConcern);
    }
    return $user_id;
}
