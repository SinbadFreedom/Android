<?php

$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$query = array(
    "listCollections" => 1,
);
$command = new MongoDB\Driver\Command($query);
$collections = $manager->executeCommand("db_tag", $command);
$collection_name_arr = [];
foreach ($collections as $collection) {
    array_push($collection_name_arr, $collection->name);
}

echo json_encode($collection_name_arr);