<?php
$relPath = "../../";

//this sets the language of the page based on your browser
include_once ($relPath . 'includes/setLang.php');

//this connects us to the database
$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

//this is so we can debug without authentication whilst building the site
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

// csrf token -- this protects you from sophisticated authentication faking
session_name('csrf_protection');
session_start();
if (empty($_SESSION['csrftoken'])) {
    $_SESSION['csrftoken'] = bin2hex(random_bytes(32));
}
$csrftoken = $_SESSION['csrftoken'];

//this converts your user data into a more readable array, so you can more easily see how we're using it
$userInfo = array(
	'username' => $attributes["http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname"][0],
	'groups' => $attributes["http://schemas.xmlsoap.org/claims/Group"],
	'email' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/emailaddress"][0],
	'firstName' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/givenname"][0],
	'lastName' => $attributes["http://schemas.xmlsoap.org/ws/2005/05/identity/claims/surname"][0]
);

//now that you are authenticated and the database is connected, we now set up the headers and navbar
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    setTextDomain('title');
    ?>
    <title>Comments | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrftoken" content="<?= $csrftoken ?>">
    <link rel="stylesheet" type="text/css" href="<?= $relPath ?>theme.css" />
</head>
<body>
<!--This script is the JQuery library, a javascript extension - directly downloaded from google-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
//this is the navbar
include_once($relPath . "navbar/navbar.php");
echo getNavBar();
?>
<div style='display: flex; justify-content: center;'>
<table>
    <tr>
        <th>Comment</th>
        <th>Response</th>
        <th>Responder</th>
    </tr>
<?php
$sql = "SELECT * FROM comment AS c
        LEFT JOIN admin AS a ON a.adminID = c.adminID
        WHERE c.adminResponse IS NOT NULL
        AND c.adminResponse <> ''
        ORDER BY c.commentID DESC;";

$statement = $db->query($sql);

while($comment = $statement->fetchObject()){
    echo "<tr><td>" . $comment->commentMessage . "</td><td>" . $comment->adminResponse . "</td><td>" . $comment->adminName . "</td></tr>";
}
?>
</table>

<style>
tr:nth-child(even) {background: #CCC}
tr:nth-child(odd) {background: #FFF}

</style>
</div>