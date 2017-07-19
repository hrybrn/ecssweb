<?php
$relPath = "../";

include_once($relPath . "../db/dbConnect.php");
include_once("getItem.php");
include_once($relPath . "navbar/navbar.php");
echo getNavBar();

if(!is_numeric($_GET['itemID'])){
    echo "Yeah dude you forgot the item ID";
    exit;
}

$id = $_GET['itemID'];

$sql = "
        SELECT *
        FROM items AS i
        WHERE i.itemID = $id;
       ";

$item = safe($sql);

?>

<script src="item.js"></script>