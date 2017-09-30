<?php
$relPath = "../";
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

$userInfo = array(
	'username' => $attributes["http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname"][0],
	'groups' => $attributes["http://schemas.xmlsoap.org/claims/Group"],
	'email' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress"][0],
	'firstName' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname"][0],
	'lastName' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname"][0]
);

$emailParts = explode("@", $userInfo['email']);

if($emailParts[1] != "ecs.soton.ac.uk"){
	echo "You're not a member of ECS";
	exit;
}

$voting = false;

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    setTextDomain('title');
    ?>
    <title><?= _('Home') ?> | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?= $relPath ?>theme.css" />
</head>
<body>
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

if(!$res = $db->query($sql)){
	//no current nomination phase, checking for voting phase
	$sql = "SELECT *
			FROM (election AS e
			INNER JOIN position AS p
			ON e.electionTypeID = p.electionTypeID)
			LEFT JOIN nomination AS n
			ON e.electionID = n.electionID
			WHERE datetime(e.votingStartDate) < datetime('now')
			AND datetime(e.votingEndDate) > datetime('now');";

	$voting = true;
	
	if(!$res = $db->query($sql)){
		//no current election is happening

		echo "No election is taking place currently, Sorry!";
		exit;
	}
}

//$res is now the db result object
//get the election object
$election = array();
while($row = $res->fetchObject()){
	$election[] = $row;
}

//voting page
if($voting){
	echo "<script src='/voting/vote.js'></script>";
} else {
	echo "<script src='/voting/nominate.js'></script>";
	$select = "<select id='roleSelect'>";
	foreach($election as $position){
		if(!isset($first)){
			$first = $position->positionID;
		}

		$select .= "<option value='" . $position->positionID . "'>" . $position->positionName . "</option>";
	}

	$select .= "</select>";

	echo $select;
	echo "<button id='submitButton' onclick='submit()'>Submit</button>";

	echo "
		<script>
			var first = " . $first . ";
			var userInfo = " . json_encode($userInfo) . ";
		</script>";
}