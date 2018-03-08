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

$positionID = $_GET['positionID'];
$name = $_GET['name'];
$manifesto = $_GET['manifesto'];

//work out which election is happening
$sql = "SELECT *
		FROM election AS e
		INNER JOIN position AS p
		ON e.electionTypeID = p.electionTypeID
		WHERE datetime(e.nominationStartDate) < datetime('now')
		AND datetime(e.nominationEndDate) > datetime('now');";

$statement =$db->query($sql);
$election = $statement->fetchObject();

//check there hasn't been another submission for this role by this person
$sql = "SELECT *
		FROM nomination AS n
		WHERE n.nominationUsername = :username
		AND n.electionID = :electionID
		AND n.positionID = :positionID;";

$statement = $db->prepare($sql);
$statement->execute([':username' => $userInfo['username'], ':electionID'=> $election->electionID, ':positionID' => $positionID]);

if($statement->fetchObject()){
	echo json_encode(['status' => false, 'message' => 'You have already submitted for this role, please email Harry Brown at <a href=\'mailto:hb15g16@soton.ac.uk\'>hb15g16@soton.ac.uk</a> to change your manifesto']);
	exit;
}

//clean manifesto and name
$manifesto = str_replace("<", "&lt;", $manifesto);
$manifesto = str_replace(">", "&gt;", $manifesto);
$manifesto = str_replace("&", "&amp;", $manifesto);

$name = str_replace("<", "&lt;", $name);
$name = str_replace(">", "&gt;", $name);
$name = str_replace("&", "&amp;", $name);


//store this
$sql = "INSERT INTO nomination(positionID, electionID, manifesto, nominationName, nominationUsername) VALUES(:positionID, :electionID, :manifesto, :nominationName, :nominationUsername);";

$statement = $db->prepare($sql);
$statement->execute([
	':positionID' => $positionID,
	':electionID' => $election->electionID,
	':manifesto' => $manifesto,
	':nominationName' => $name,
	':nominationUsername' => $userInfo['username']
]);

$sql = "select last_insert_rowid() AS nominationID;";

$statement = $db->query($sql);
$res = $statement->fetchObject();


echo json_encode(['status' => true, 'nominationID' => $res->nominationID, 'message' => 'Thank you ' . $name . " for your nomination in this election."]);
