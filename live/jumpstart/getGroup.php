<?php

$relPath = "../";

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

$groupID = $_GET['groupID'];

$sql = "SELECT j.name, j.helper
		FROM jumpstart AS j
		WHERE j.groupID = :groupID";

$res = $db->prepare($sql);
$res->execute(array(':groupID' => $groupID));

$group = array();
$group['helpers'] = array();
$group['freshers'] = array();

while($row = $res->fetchObject()){
	if(!$row->helper){
		$group['freshers'][] = $row;
	}
}

echo json_encode($group);