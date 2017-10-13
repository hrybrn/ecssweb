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

$sql = "SELECT a.adminID
        FROM admin AS a
        WHERE a.username = :username;";

$statement = $db->prepare($sql);
$statement->execute(array(':username' => $userInfo['username']));

if(!$user = $statement->fetchObject()){
    echo "user " . $username . " doesn't have permissions for this page";
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
<script src="/voting/results/results.js" type="text/javascript"></script>
<script src="/jquery.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function(){
        load();
    });
</script>

<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();

$sql = "SELECT *
        FROM election AS e
        INNER JOIN electionType AS et
        ON e.electionTypeID = et.electionTypeID
        WHERE datetime(e.votingEndDate) < datetime('now');";

$statement = $db->query($sql);

$elections = [];
while($election = $statement->fetchObject()){
    $elections[$election->electionID] = $election;
}

echo "<select id='electionSelect'>";

foreach($elections as $electionID => $election){
    $year = new DateTime($election->votingEndDate);
    $year = $year->format("Y");
    echo "<option value='" . $electionID . "'>" . $election->electionName . " " . $year . "</option>";
}

echo "</select>";
echo "<div id='resultsDiv'></div>";