<?php
$relPath = "../../";
include_once ($relPath . 'includes/setLang.php');

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

$emailParts = explode("@", $userInfo['email']);

if(!(in_array("fpStudent", $userInfo['groups']) || in_array("fpStaff", $userInfo['groups']))){
	echo json_encode(['status' => false, 'message' => "You're not a member of ECS"]);
	exit;
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    setTextDomain('title');
    ?>
    <title>Voting | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrftoken" content="<?= $csrftoken ?>">
    <link rel="stylesheet" type="text/css" href="<?= $relPath ?>theme.css" />
</head>
<body>
<script src='/jquery.js'></script>
<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();

//check for the existence of a election that is in nomination phase
$sql = "SELECT *
		FROM election AS e
		INNER JOIN position AS p
		ON e.electionTypeID = p.electionTypeID
		WHERE datetime(e.nominationStartDate) < datetime('now')
		AND datetime(e.nominationEndDate) > datetime('now');";

$res = $db->query($sql);
$election = $res->fetchObject();

if(!$election){
    echo "No election is currently taking place.";
    exit;
}

//find all current nominations grouped by position
$sql = "SELECT * from nomination as n
        INNER JOIN position as p on
        p.positionID = n.positionID
        WHERE n.electionID = :electionID;";

$res = $db->prepare($sql);
$res ->execute([':electionID' => $election->electionID]);

$positionData = [];

while($row = $res->fetchObject()){
    $positionData[] = $row;
}

if(!$positionData){
    echo "No one has nominated themselves yet in this election.";
    exit;
}

$positions = [];

foreach($positionData as $position){
    if(!isset($positions[$position->positionName])){
        $positions[$position->positionName] = [];
    }
    $positions[$position->positionName][$position->nominationID] = $position->nominationName;
}

//find if we are currently supporting someone
$sql = "SELECT n.nominationName AS nomName, p.positionName AS posName
        FROM (nomination AS n
            INNER JOIN position AS p
            ON n.positionID = p.positionID)
        INNER JOIN support AS s
        ON n.nominationID = s.nominationID
        WHERE s.supportUsername = :username";

$statement = $db->prepare($sql);
$statement->execute([':username' => $userInfo['username']]);

if($support = $statement->fetchObject()){
    $currentPerson = $support->nomName . " for " . $support->posName;
} else {
    $currentPerson = "no one";
}

//generate a supportToken
$token = new DateTime('now');
$token = $token->format(DateTime::RFC1123) . $userInfo['email'];
$token = hash('sha256', $token);

$sql = "INSERT INTO supportToken(supportToken, supportEmail, supportTokenUsed) VALUES(:token, :email, :used);";
$statement = $db->prepare($sql);
$statement->execute([':email' => $userInfo['email'], ':token' => $token, ':used' => 0]);
?>
<style>
#centredDiv {
    text-align: center;
    width: 90%;
    margin-left: 5%;
}
</style>
<div id='centredDiv'>
<h3>Who do you want to support in our annual general election?</h3>
<h2>You can only support <b>one person</b>, and you are currently supporting: <?= $currentPerson ?></h2>
<?php

ksort($positions);

foreach($positions as $positionName => $data){
    echo "<div><h3>" . $positionName . "</h3>";
    foreach($data as $nominationID => $nominationName){
        echo "<button onclick='select(" . $nominationID . ")'>" . $nominationName . "</button>";
    }
    echo "</div>";
}
?></div>

<script>
var token = '<?= $token ?>';
function select(nominationID){
    $.ajax({
		url: "/voting/support/support.php",
		type: 'post',
		data: {'nominationID': nominationID, 'token': token},
		dataType: 'json',
		success: function(result){
			location.reload();
		}
	});
}
</script>
