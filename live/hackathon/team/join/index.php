<?php
$relPath = "../../../";
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
    <title><?= $hackathonEvent->hackathonEventName ?> Team Sign Up | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrftoken" content="<?= $csrftoken ?>">
    <link rel="stylesheet" type="text/css" href="/theme.css" />
    <link rel="stylesheet" type="text/css" href="/hackathon/signup.css" />
</head>
<body>
<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();

$sql = "SELECT *
        FROM hackathonPerson AS hp
        LEFT JOIN hackathonTeam AS ht
        ON hp.hackathonTeamID = ht.hackathonTeamID
        WHERE hp.hackathonPersonEmail = '" . $userInfo['email'] . "';";

$statement = $db->query($sql);
$person = $statement->fetchObject();
?>
<script src="/jquery.js"></script>

<?php if($person->hackathonTeamID): ?>

<div id='formDiv'>
    <h3><?= $hackathonEvent->hackathonEventName ?> Team Sign Up Page</h3>
    <p>You need to leave your current team <b>before</b> you can register for another</p>
    <button onclick='window.location.href="hackathon/team/manage"'>Manage Team</button>
</div>

<?php exit(); endif; ?>

<?php if($person):

if(isset($_GET['token'])){
    $token = $_GET['token'];
} else {
    $token = "";
}
?>

<div id='formDiv'>
    <h3><?= $hackathonEvent->hackathonEventName ?> Team Sign Up Page</h3>
    <p><?= $hackathonEvent->hackathonEventInfo ?></p>
    <p>
        ~Team Sign Up Info~
    </p>
    <table>
        <tr>
            <td>
                <label>Team Join Token</label>
            </td>
            <td>
                <input type='text' id='teamtoken' value='<?= $token ?>'></input>
            </td>
        </tr>
        <tr>
            <td id='submit' colspan='2'>
                <button onclick='submit()'>Submit</button>
            </td>
        </tr>
    </table>

    <script src="/hackathon/team/join/join.js"></script>
</div>

<?php else: ?>

<div id='formDiv'>
    <h3><?= $hackathonEvent->hackathonEventName ?> Team Sign Up Page</h3>
    <p>You need to sign up as an individual <b>before</b> you can join a team</p>
    <button onclick='window.location.href="/hackathon/person/signup"'>Individual Sign Up</button>
</div>

<?php endif;?>