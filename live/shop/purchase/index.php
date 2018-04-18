<?php
$relPath = "../../";
include_once($relPath . "auth/forcelogin.php");
include_once($relPath . "auth/includedb.php");

//society select
if(in_array('fpStudent', $userInfo['groups'])){
    $society = "ECSS";
}

if(in_array('ebStudent', $userInfo['groups'])){
    $society = "Chemistry";
}

if(in_array('peStudent', $userInfo['groups'])){
    $society = "SUES";
}

if(!isset($society) | !isset($_GET['purchaseID'])){
    header( 'Location: /shop/item?itemID=10');
}

$sql = "UPDATE purchase
        SET purchased = 1
        WHERE society = :society
        AND username = :username
        AND purchaseID = :purchaseID";

$statement = $db->prepare($sql);
$statement->execute([
    ':society' => $society,
    ':username' => $userInfo['username'],
    ':purchaseID' => $_GET['purchaseID']
]);

header( 'Location: /shop/item?itemID=10');