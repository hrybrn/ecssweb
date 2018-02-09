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

$sql = "SELECT *
FROM hackathonEvent AS he
WHERE datetime(he.hackathonApplicationStartDate) < datetime('now')
AND datetime(he.hackathonApplicationEndDate) > datetime('now')";

$statement = $db->query($sql);

$hackathonEvent = $statement->fetchObject();

if($hackathonEvent == null){
    header('Location: /');
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    setTextDomain('title');
    ?>
    <title><?= $hackathonEvent->hackathonEventName ?> Directory | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrftoken" content="<?= $csrftoken ?>">
    <link rel="stylesheet" type="text/css" href="<?= $relPath ?>theme.css" />
</head>
<body>
<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();
?>
<script src="/jquery.js"></script>
<link rel="stylesheet" type="text/css" href="/hackathon/hackathon.css" />

<div id='blurb'>
    <img id='logo' src='<?= $hackathonEvent->hackathonEventLogo ?>'>
    <p><?= $hackathonEvent->hackathonEventInfo ?></p>
</div>
<div id='menu'>
    <button onclick='window.location.href="/hackathon/team/signup"'>Team Sign Up</button>
    <button onclick='window.location.href="/hackathon/person/signup"'>Individual Sign Up</button>
    <button onclick='window.location.href="/hackathon/team/manage"'>Manage Team</button>
    <button onclick='window.location.href="/hackathon/person/manage"'>Manage Individual</button>
</div>