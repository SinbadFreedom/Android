<?php
date_default_timezone_set('PRC');

/** 初始化标题集合*/
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$db_collection_name = 'db_account.col_user';
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->update(
    [],
    ['$set' => ['exp_day' => 0]],
    ['multi' => true, 'upsert' => false]
);
$result = $manager->executeBulkWrite($db_collection_name, $bulk);

echo "ok";