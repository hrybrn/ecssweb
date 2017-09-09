<?php
$relPath = "../";

$dbLoc = realpath($relPath . "../db/ecss.db");

$db = new PDO('sqlite:' . $dbLoc);

//check for the existence of a election that is in nomination phase
$sql = "SELECT *
		FROM election AS e
		WHERE datetime(e.nominationStartDate) < datetime(now)
		AND datetime(e.nominationEndDate) > datetime(now);";

if(!$res = $db->query($sql)){
	//no current nomination phase, checking for voting phase
	$sql = "SELECT *
			FROM election AS e
			WHERE datetime(e.votingStartDate) < datetime(now)
			AND datetime(e.votingEndDate) > datetime(now);";

	$voting = true;
	
	if(!$res = $db->query($sql)){
		//no current election is happening
		exit;
	}
}

//$res is now the db result object
//get the election object
$election = $res->fetchObject();

