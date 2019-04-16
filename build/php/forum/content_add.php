<?php
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/3/20
 * Time: 1:03
 */
error_reporting(E_ALL ^ E_NOTICE);

$time_stamp = time();
$file = '../../log/log_content_add_' . date('Y-m-d', $time_stamp) . '.txt';
$content = file_get_contents("php://input");
$content = $content . " $time_stamp\n";
file_put_contents($file, $content, FILE_APPEND);

if (!isset($_POST['num'])) {
    echo 'param error 0';
    return;
}

if (!isset($_POST['content'])) {
    echo 'param error 1';
    return;
}

if (!isset($_POST['userid'])) {
    echo 'param error 3';
    return;
}

if (!isset($_POST['name'])) {
    echo 'param error 4';
    return;
}

if (!isset($_POST['tag'])) {
    echo 'param error 5';
    return;
}

if (!is_numeric($_POST['num'])) {
    echo 'param error 6';
    return;
}

$tag = $_POST['tag'];
$num = $_POST['num'];
$content = $_POST['content'];
$user_id = $_POST['userid'];
$nick_name = $_POST['name'];

/** collection name*/
$col_name = 'db_content.'. $tag . '_' . $num;

$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert(['content' => $content, 'userid' => $user_id, 'name' => $nick_name, 'time' => $time_stamp]);

$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 3000);
$res = $manager->executeBulkWrite($col_name, $bulk, $writeConcern);