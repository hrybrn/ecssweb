<?php
$relPath = "../../../";

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

// csrf token
session_name('csrf_protection');
session_start();
if (empty($_SESSION['csrftoken'])) {
    $_SESSION['csrftoken'] = bin2hex(random_bytes(32));
}
$csrftoken = $_SESSION['csrftoken'];

$userInfo = array(
	'username' => $attributes["http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname"][0],
	'groups' => $attributes["http://schemas.xmlsoap.org/claims/Group"],
	'email' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress"][0],
	'firstName' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname"][0],
	'lastName' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname"][0]
);

$sql = "SELECT ht.hackathonTeamID AS id
        FROM (
            hackathonTeam AS ht
            INNER JOIN hackathonPerson AS hp
            ON ht.hackathonTeamLeaderID = hp.hackathonPersonID)
        INNER JOIN hackathonEvent AS he
        ON ht.hackathonEventID = he.hackathonEventID
        WHERE datetime(he.hackathonApplicationStartDate) < datetime('now')
        AND datetime(he.hackathonApplicationEndDate) > datetime('now')
        AND hackathonPersonEmail = :email;";

$statement = $db->prepare($sql);
$statement->execute([':email' => $userInfo['email']]);

if(!$team = $statement->fetchObject()){
    echo json_encode(['status' => false, 'message' => 'You are not a team leader.']);
    exit;
}

$hashID = $_POST['hashID'];
if(!is_numeric($hashID)){
    echo json_encode(['status' => false, 'message' => 'Not a valid hash ID.']);
    exit;
}

$sql = "UPDATE hackathonHash SET hackathonHashExpired = 1 WHERE hackathonTeamID = :teamID AND hackathonHashID = :hashID;";
$statement = $db->prepare($sql);
$params = [':teamID' => $team->id, ':hashID' => $hashID];
$statement->execute($params);

echo json_encode(['status' => true, 'message' => 'Successfully expired']);