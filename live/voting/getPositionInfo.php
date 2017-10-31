<?php

$relPath = "../";

$positionID = $_GET['positionID'];

$dbLoc = realpath($relPath . "../db/ecss.db");

$db = new PDO('sqlite:' . $dbLoc);

$sql = "SELECT *
		FROM position AS p
		WHERE p.positionID = :positionID";

$statement = $db->prepare($sql);
$statement->execute(array(':positionID' => $positionID));

if($result = $statement->fetchObject()){
	$result->status = true;
	echo json_encode($result);
} else {
	echo json_encode(array('status' => false));
}