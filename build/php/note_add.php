<?php
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/3/20
 * Time: 1:03
 */
$time_stamp = time();
$file = 'log_note_add_' . date('Y-m-d', $time_stamp) . '.txt';
$content = file_get_contents("php://input");
$content = $content . " $time_stamp\n";
file_put_contents($file, $content, FILE_APPEND);

if (!isset($_POST['num'])) {
    echo 'param error 0';
    return;
}

if (!isset($_POST['note'])) {
    echo 'param error 1';
    return;
}

if (!isset($_POST['openid'])) {
    echo 'param error 2';
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

$num = $_POST['num'];
$note = $_POST['note'];
$open_id = $_POST['openid'];
$user_id = $_POST['userid'];
$nick_name = $_POST['name'];

if (!is_numeric($num)) {
    echo 'param error 5';
    return;
}

/** collection name*/
$db_collection_name = 'python3.note_' . $num;

$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert(['note' => $note, 'openid' => $open_id, 'userid' => $user_id, 'name' => $nick_name, 'time' => $time_stamp]);

$writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 3000);//可选，修改确认
$res = $manager->executeBulkWrite($db_collection_name, $bulk, $writeConcern);