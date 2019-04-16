<?php

if (!isset($_GET['tag'])) {
    echo 'param error 0';
    return;
}

/** collection name*/
$tag = $_GET['tag'];

$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$query = array(
    "create" => $tag,
);
$command = new MongoDB\Driver\Command($query);
$command_cursor = $manager->executeCommand("db_tag", $command);
/** 笔记总条数*/
$res = $command_cursor->toArray()[0];
var_dump($res);