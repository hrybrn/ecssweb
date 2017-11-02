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

if(!(in_array("fpStudent", $userInfo['groups']) || in_array("fpStaff", $userInfo['groups']) || in_array("vnStudent", $userInfo['groups']) || in_array("vnStaff", $userInfo['groups']))){
	echo json_encode(['status' => false, 'message' => "You're not a member of ECS"]);
	exit;
}

$name = $_GET['name'];
$year = (string) $_GET['year'];
$course = $_GET['course'];

$failure = ['status' => false, 'message' => 'Something went wrong with your application.'];
$success = ['status' => true, 'message' => 'Info uploaded successfully. Now uploading your CV and cover letter.'];

if($name == "" | $year == "" | $course == ""){
    echo json_encode($failure);
    exit;
}
//check for previous applications
$sql = "SELECT *
        FROM application AS a
        INNER JOIN company AS c
        ON a.companyID = c.companyID
        WHERE datetime(c.applicationStartDate) < datetime('now')
        AND datetime(c.applicationEndDate) > datetime('now')
        AND a.applicationUsername = :username";

$statement = $db->prepare($sql);
$statement->execute([':username' => $userInfo['username']]);

if($statement->fetchObject()){
    echo json_encode($failure);
    exit;
}
//work out the companyID
$sql = "SELECT *
        FROM company AS c
        WHERE datetime(c.applicationStartDate) < datetime('now')
        AND datetime(c.applicationEndDate) > datetime('now')";

$statement = $db->query($sql);
$company = $statement->fetchObject();

if(!$company){
    echo json_encode($failure);
    exit;
}

//now save the new application
$sql = "INSERT INTO application(
            companyID,
            applicationName,
            applicationUsername,
            applicationEmail,
            applicationCourse,
            applicationYear) VALUES(
            :companyID,
            :applicationName,
            :applicationUsername,
            :applicationEmail,
            :applicationCourse,
            :applicationYear);";

$statement = $db->prepare($sql);
$statement->execute([
    ":companyID" => $company->companyID,
    ":applicationName" => $name,
    ":applicationUsername" => $userInfo['username'],
    ":applicationEmail" => $userInfo['email'],
    ":applicationCourse" => $course,
    ":applicationYear" => $year
]);

$sql = "SELECT last_insert_rowid() as applicationID;";
$statement = $db->query($sql);

$result = $statement->fetchObject();
//echo json_encode($result);

$success['applicationID'] = (int)$result->applicationID;

echo json_encode($success);