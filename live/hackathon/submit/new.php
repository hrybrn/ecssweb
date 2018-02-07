<?php
$relPath = "../../";

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

$sql = [
    'person' => 'INSERT INTO hackathonPerson(
                    hackathonPersonName,
                    hackathonPersonEmail,
                    hackathonPersonCourse,
                    hackathonPersonGraduation,
                    hackathonPersonTShirtSize,
                    hackathonPersonDietComments) VALUES(
                    :hackathonPersonName,
                    :hackathonPersonEmail,
                    :hackathonPersonCourse,
                    :hackathonPersonGraduation,
                    :hackathonPersonTShirtSize,
                    :hackathonPersonDietComments);',
    
    'team' => 'INSERT INTO hackathonTeam(
                    hackathonEventID,
                    hackathonName,
                    hackathonTeamLeaderID,
                    hackathonMatchmaking) VALUES(
                    :hackathonEventID,
                    :hackathonName,
                    :hackathonTeamLeaderID,
                    :hackathonMatchmaking);'
];

$expected = [
    'person' => [
        'hackathonPersonName',
        'hackathonPersonCourse',
        'hackathonPersonGraduation',
        'hackathonPersonTShirtSize',
        'hackathonPersonDietComments'
    ],

    'team' => [
        'hackathonName',
        'hackathonMatchmaking'
    ]    
];

$type = $_POST['type'];
$params = $_POST['params'];

//todo
//error checking
$failed = false;

//generic
foreach($expected[$type] as $param){
    if(!isset($params[$param])){
        $failed = true;
    }
}

//specific
if($type == 'person'){
    if(!isset($params['hackathonPersonName'])){
        $params['hackathonPersonName'] = $userInfo['firstName'] . " " . $userInfo['lastName'];
    }
    if(!isset($params['hackathonPersonEmail'])){
        $params['hackathonPersonEmail'] = $userInfo['email'];
    }
}

if($type == 'team'){
    $leaderFinder = "SELECT hp.hackathonPersonID AS id
                     FROM hackathonPerson AS hp
                     WHERE hp.hackathonPersonEmail = :email;";
    
    $statement = $db->prepare($leaderFinder);
    $statement->execute([':email' => $userInfo['email']]);

    if($leader = $statement->fetchObject()){
        $params['hackathonTeamLeaderID'] = $leader->id;
    } else {
        $failed = true;
    }

    $eventSql = "SELECT he.hackathonEventID as id
                 FROM hackathonEvent AS he
                 WHERE datetime(he.hackathonApplicationStartDate) < datetime('now')
                 AND datetime(he.hackathonApplicationEndDate) > datetime('now')";

    $statement = $db->query($eventSql);

    if($hackathonEvent = $statement->fetchObject()){
        $params['hackathonEventID'] = $hackathonEvent->id;
    } else {
        $failed = true;
    }
}

if($type != "team" && $type != "person"){
    $failed = true;
}

if($failed){
    echo json_encode(['status' => false, 'message' => 'something went very wrong']);
    exit;
}

$statement = $db->prepare($sql[$type]);
$statement->execute($params);

if($type == 'team'){
    $sql = "SELECT ht.hackathonTeamID AS id
            FROM hackathonTeam AS ht
            WHERE hackathonEventID = :eventID
            ORDER BY hackathonTeamID DESC;";

    $statement = $db->prepare($sql);
    $statement->execute([':eventID' => $hackathonEvent->id]);
    $teamID = $statement->fetchObject();

    $sql = "UPDATE hackathonPerson SET hackathonTeamID = :teamID WHERE hackathonPersonEmail = :email;";
    $statement = $db->query($sql);
    $statement->execute([':teamID' => $teamID->id, ':email' => $userInfo['email']]);
}
echo json_encode(['status' => true, 'message' => 'Sign up successful. Use one of the links below for the next steps.']);