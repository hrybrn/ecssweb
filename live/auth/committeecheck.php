<?php

require_once($relPath . "auth/includedb.php");

//check for committeee
$sql = "SELECT *
        FROM admin AS a
        WHERE a.username = :username";

$statement = $db->prepare($sql);
$statement->execute([':username' => $userInfo['username']]);

if(!$statement->fetchObject()){
  echo "You're not a committee member sorry!";
}
