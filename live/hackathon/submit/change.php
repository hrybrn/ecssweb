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

$available = [
    'person' => [
        'hackathonPersonName',
        'hackathonPersonCourse',
        'hackathonPersonGraduation',
        'hackathonPersonTShirtSize',
        'hackathonPersonDietComments'
    ],

    'team' => [
        'hackathonName',
        'hackathonTeamLeader',
        'hackathonMatchmaking'
    ]    
];

$type = $_POST['type'];
$params = $_POST['params'];

//error checking
$failed = false;

//generic
foreach($params as $paramName => $param){
    if(!in_array($paramName, $available[$type])){
        $failed = true;
    }
}

//specific sql generation
if($type == 'person'){
    $sql = "UPDATE hackathonPerson SET";

    foreach($params as $paramName => $param){
        $sql .= " " . $paramName . " = :" . $paramName . ",";
    }
    $sql = substr($sql, 0, strlen($sql) - 1);

    $sql .= ";";
}

if($type == 'team'){
    $identityCheck = "SELECT ht.hackathonTeamLeaderID
                      FROM hackathonTeam AS ht
                      INNER JOIN hackathonPerson AS hp
                      ON ht.hackathonTeamLeaderID = hp.hackathonPersonID
                      WHERE hp.hackathonPersonEmail = :email";
    
    $statement = $db->prepare($identityCheck);
    $statement->execute([':email' => $userInfo['email']]);

    if(!$statement->fetchObject()){
        $failed = true;
    }

    $sql = "UPDATE hackathonTeam SET";

    foreach($params as $paramName => $param){
        $sql .= " " . $paramName . " = :" . $paramName . ",";
    }
    
    $sql = substr($sql, 0, strlen($sql) - 1);

    $sql .= ";";
}

if($failed){
    echo json_encode(['status' => false, 'message' => 'something went very wrong']);
    exit;
}

$statement = $db->prepare($sql);
$statement->execute($params);

echo json_encode(['status' => true, 'message' => 'changed correctly']);