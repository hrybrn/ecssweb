<?php

$relPath = "../";

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

$search = $_GET['search'];

//we search on group
if(is_numeric($search)){
	$sql = "SELECT *
			FROM jumpstart AS j
			INNER JOIN helper AS h
			ON j.memberID = h.memberID
			WHERE j.helper = 1
			AND j.groupID = :group;";

	$statement = $db->prepare($sql);
	$statement->execute(array(':group' => $search));
	$results = array();

	while($rowObject = $statement->fetchObject()){
		$results[] = $rowObject;
	}
} 
//we search on name
else{
	$sql = "SELECT k.*, h.*
			FROM (jumpstart AS j
			INNER JOIN jumpstart AS k ON j.groupID = k.groupID)
			INNER JOIN helper AS h ON k.memberID = h.memberID
			WHERE k.helper = 1
			AND j.name LIKE '%' || trim(:name) || '%';";

	$statement = $db->prepare($sql);
	$statement->execute(array(':name' => $search));
	$results = array();

	while($rowObject = $statement->fetchObject()){
		$results[] = $rowObject;
	}
}

if(empty($results)){
	$sql = "SELECT *
		FROM jumpstart AS j
		INNER JOIN helper AS h
		ON j.memberID = h.memberID
		WHERE j.helper = 1;";

	$statement = $db->query($sql);
	$results = array();

	while($rowObject = $statement->fetchObject()){
		$results[] = $rowObject;
	}
}

echo json_encode($results);