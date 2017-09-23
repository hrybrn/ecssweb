<?php

$relPath = "../../";

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

$search = $_GET['search'];

//we search on group
if(is_numeric($search)){
	$sql = "SELECT *
			FROM (jumpstart AS j
			INNER JOIN helper AS h ON j.memberID = h.memberID)
			INNER JOIN jumpstartGroup AS g ON j.groupID = g.groupID
			WHERE j.helper = 1
			AND j.groupID = :group;";

	$statement = $db->prepare($sql);
	$statement->execute(array(':group' => $search));
	$results = array();

	while($rowObject = $statement->fetchObject()){
		$results[] = $rowObject;
	}
} 
//we search on fresher/helper name
else{
	$search = preg_replace('/\s+/', ' ', $search);

	$sql = "SELECT k.*, h.*, g.groupName
			FROM ((jumpstart AS j
			INNER JOIN jumpstart AS k ON j.groupID = k.groupID)
			INNER JOIN helper AS h ON k.memberID = h.memberID)
			INNER JOIN jumpstartGroup AS g ON j.groupID = g.groupID
			WHERE k.helper = 1
			AND j.memberName LIKE '%' || trim(:name) || '%';";

	$statement = $db->prepare($sql);
	$statement->execute(array(':name' => $search));
	$results = array();

	while($rowObject = $statement->fetchObject()){
		$results[] = $rowObject;
	}
}

//we search on group name
if(empty($results)){
	$sql = "SELECT *
			FROM (jumpstart AS j
			INNER JOIN helper AS h ON j.memberID = h.memberID)
			INNER JOIN jumpstartGroup AS g ON j.groupID = g.groupID
			WHERE j.helper = 1
			AND g.groupName LIKE '%' || trim(:groupName) || '%';";

	$statement = $db->prepare($sql);
	$statement->execute(array(':groupName' => $search));
	$results = array();

	while($rowObject = $statement->fetchObject()){
		$results[] = $rowObject;
	}
}

//if its not good then we return them all
if(empty($results)){
	$sql = "SELECT *
		FROM (jumpstart AS j
		INNER JOIN helper AS h ON j.memberID = h.memberID)
		INNER JOIN jumpstartGroup AS g ON j.groupID = g.groupID
		WHERE j.helper = 1;";

	$statement = $db->query($sql);
	$results = array();

	while($rowObject = $statement->fetchObject()){
		$results[] = $rowObject;
	}
}

echo json_encode($results);