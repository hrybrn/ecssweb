<?php

//
$relPath = "../";

include_once($relPath . "db/dbConnect.php");

if(!is_numeric($_GET['itemID'])){
    return false;
    exit;
}

$id = $_GET['itemID'];

$sql = "
        SELECT *
        FROM items AS i
        WHERE i.itemID = $id;
       ";

return safe($sql);