<?php
$relPath = "../";

$maxItems = 20;

include_once($relPath . "../db/dbConnect.php");

if(!(isset($_GET['search']))){
    return false;
    exit;
}

$search = $_GET['search'];

$sql = "
        SELECT *
        FROM items AS i
        WHERE i.name LIKE '%$search%'
        LIMIT $maxItems;
       ";

return safe($sql);