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

if(!(in_array("fpStudent", $userInfo['groups']) || in_array("fpStaff", $userInfo['groups']))){
	echo json_encode(['status' => false, 'message' => "You're not a member of ECS"]);
	exit;
}

// check csrf token
session_name('csrf_protection');
session_start();
if (!hash_equals($_SESSION['csrftoken'], $_POST['csrftoken'])) {
    echo json_encode(['status' => false, 'message' => "Authorization failed."]);
    exit();
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

$hash = hash('sha256', $userInfo['username']);

//check for previous entries
$sql = "SELECT *
		FROM voted AS v
		WHERE v.voteHash = :hash
		AND v.electionID = :electionID
		AND v.positionID = :positionID;";

$statement = $db->prepare($sql);
$statement->execute([':hash' => $hash, ':electionID' => $election->electionID, ':positionID' => $positionID]);

if($statement->fetchObject()){
	echo json_encode(['status' => true, 'message' => "You have already voted for this position in this election."]);
	exit;
}

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$random = getRandom();

function getRandom(){
  global $db;
  $random = rand();
  $sql = "INSERT INTO voter VALUES(:random);";
  $statement = $db->prepare($sql);
  try{
    $statement->execute([':random' => $random]);
    return $random;
  } catch (PDOException $e){
    return getRandom();
  }
}

//work out date
$date = new DateTime();
$date = $date->format(DateTime::RFC850);

$sql = "INSERT INTO vote(nominationID, voterID, ranking, voteDate) VALUES(:nominationID, :voterID, :ranking, :voteDate);";

foreach($entryData as $rank => $nominationID){
	$statement = $db->prepare($sql);
	$statement->execute([
        ':voterID' => $random,
        ':nominationID' => $nominationID,
        ':ranking' => $rank,
        ':voteDate' => $date
  ]);
}
$combinedHash = hash("sha256", $userInfo['username'] . "pos" . $positionID . "elec" . $election->electionID);

//log that you voted
$sql = "INSERT INTO voted(positionID, electionID, voteHash, combinedHash) VALUES (:positionID, :electionID, :voteHash, :combinedHash);";
$statement = $db->prepare($sql);
$statement->execute([
    ':positionID' => $positionID,
    ':electionID' => $election->electionID,
    ':voteHash' => $hash,
    ':combinedHash' => $combinedHash
]);

echo json_encode(['status' => true, 'message' => "Thank you for your vote"]);
