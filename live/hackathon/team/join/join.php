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

$userInfo = array(
	'username' => $attributes["http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname"][0],
	'groups' => $attributes["http://schemas.xmlsoap.org/claims/Group"],
	'email' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress"][0],
	'firstName' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname"][0],
	'lastName' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname"][0]
);

$token = $_POST['token'];

$sql = "SELECT hh.hackathonTeamID AS teamID, ht.hackathonName AS teamName
        FROM hackathonHash AS hh
        INNER JOIN (hackathonTeam AS ht
            INNER JOIN hackathonEvent AS he
            ON ht.hackathonEventID = he.hackathonEventID)
        ON hh.hackathonTeamID = ht.hackathonTeamID
        WHERE hh.hackathonHashExpired = 0
        AND datetime(he.hackathonApplicationStartDate) < datetime('now')
        AND datetime(he.hackathonApplicationEndDate) > datetime('now')
        AND hh.hackathonHash = :token;";

$statement = $db->prepare($sql);
$statement->execute([':token' => $token]);

if($team = $statement->fetchObject()){
    $sql = "UPDATE hackathonPerson SET hackathonTeamID = :teamID WHERE hackathonPersonEmail = :email";
    $statement = $db->prepare($sql);
    $statement->execute([':teamID' => $team->teamID, ':email' => $userInfo['email']]);
    echo json_encode(['status' => true, 'message' => 'Successfully joined ' . $team->teamName]);
} else {
    echo json_encode(['status' => false, 'message' => 'Token was either invalid or has been expired by your team leader.']);
}