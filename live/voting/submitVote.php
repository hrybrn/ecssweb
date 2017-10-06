<?php

$relPath = "../";

$dbLoc = realpath($relPath . "../db/ecss.db");

$db = new PDO('sqlite:' . $dbLoc);

require_once($relPath . '../config/config.php');

if (DEBUG) {
    //debug version
    $attributes = [
    	"http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname" => array("hb15g16"),
    	"http://schemas.xmlsoap.org/claims/Group" => array(
    		"Domain Users",
    		"allStudent",
    		"fpStudent",
    		"jfNISSync",
    		"fpappvmatlab2009b",
    		"AllStudentsMassEmail",
    		"f7_All_Faculty_Student",
    		"ebXRDDataSharedResourceRead",
    		"isiMUSI2015Users",
    		"jfSW_Deploy_OpenChoiceDesktop_2.2_SCCM"
    		),
    	"http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress" => array("hb15g16@ecs.soton.ac.uk"),
    	"http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname" => array("Harry"),
    	"http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname" => array("Brown")
    ];
} else {
    //live version
    require_once('/var/www/auth/lib/_autoload.php');
    $as = new SimpleSAML_Auth_Simple('default-sp');
    $as->requireAuth();
    $attributes = $as->getAttributes();
}

$userInfo = array(
	'username' => $attributes["http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname"][0],
	'groups' => $attributes["http://schemas.xmlsoap.org/claims/Group"],
	'email' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress"][0],
	'firstName' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname"][0],
	'lastName' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname"][0]
);

$emailParts = explode("@", $userInfo['email']);

if($emailParts[1] != "ecs.soton.ac.uk"){
	echo json_encode(['status' => false, 'message' => "You're not a member of ECS"]);
	exit;
}

$entryData = $_POST['entryData'];
$positionID = $_POST['positionID'];

//check that nominationIDs match up with positionIDs
$sql = "SELECT *
		FROM nomination AS n
		WHERE n.positionID = :positionID
		AND n.nominationID = :nominationID;";

$valid = true;

foreach($entryData as $rank => $nominationID){
	$statement = $db->prepare($sql);
	$statement->execute([':positionID' => $positionID, ':nominationID' => $nominationID]);

	if(!$statement->fetchObject()){
		$valid = false;
	}
}

if(!$valid){
	echo json_encode(['status' => false, 'message' => "Not a valid entry"]);
	exit;
}

//work out what election is happening (assume only one is happening at any one time)
$sql = "SELECT *
		FROM election AS e
		WHERE datetime(e.votingStartDate) < datetime('now')
		AND datetime(e.votingEndDate) > datetime('now');";

$statement = $db->query($sql);
$election = $statement->fetchObject();

//check for previous entries
$sql = "SELECT *
		FROM vote AS v
		INNER JOIN nomination AS n
		ON v.nominationID = n.nominationID
		WHERE v.voteUsername = :username
		AND n.electionID = :electionID
		AND n.positionID = :positionID;";

$statement = $db->prepare($sql);
$statement->execute([':username' => $userInfo['username'], ':electionID' => $election->electionID, ':positionID' => $positionID]);

if($statement->fetchObject()){
	echo json_encode(['status' => true, 'message' => "You have already voted for this position in this election."]);
	exit;
}

//store votes

$sql = "INSERT INTO vote(nominationID, voteUsername, ranking) VALUES(:nominationID, :voteUsername, :ranking);";

foreach($entryData as $rank => $nominationID){
	$statement = $db->prepare($sql);
	$statement->execute([':voteUsername' => $userInfo['username'], ':nominationID' => $nominationID, ':ranking' => $rank]);
}


echo json_encode(['status' => true, 'message' => "Thank you for your vote"]);