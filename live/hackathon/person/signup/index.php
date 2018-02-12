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

$dietMessage = "If you have anything else to add about your dietary requirements then please highlight them here";
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    setTextDomain('title');
    ?>
    <title><?= $hackathonEvent->hackathonEventName ?> Individual Sign Up | ECSS</title>
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
        WHERE hp.hackathonPersonEmail = '" . $userInfo['email'] . "';";

$statement = $db->query($sql);
$person = $statement->fetchObject();

if(!$person):?>
<script src="/jquery.js"></script>
<script src="/hackathon/person/signup/signup.js"></script>
<div id='formDiv'>
    <h3><?= $hackathonEvent->hackathonEventName ?> Individual Sign Up Page</h3>
    <p><?= $hackathonEvent->hackathonEventInfo ?></p>
    <p>
        ~Sign Up Info~
    </p>
    <table>
        <tr>
            <td>
                <label>Name</label>
            </td>
            <td>
                <input type='text' id='name' value='<?= $userInfo['firstName'] . " " . $userInfo['lastName'] ?>'></input>
            </td>
        </tr>
        <tr>
            <td>
                <label>Course</label>
            </td>
            <td>
                <input type='text' id='course'></input>
            </td>
        </tr>
        <tr>
            <td>
                <label>Graduation Year</label>
            </td>
            <td>
                <select id='gyear'>
                    <option value='select'>Select</option>
                    <option value='2018'>2018</option>
                    <option value='2019'>2019</option>
                    <option value='2020'>2020</option>
                    <option value='2021'>2021</option>
                    <option value='2022'>2022</option>
                    <option value='2023'>2023</option>
                    <option value='2024'>2024</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label>T-Shirt Size</label>
            </td>
            <td>
                <select id='tshirtsize'>
                    <option value='select'>Select</option>
                    <option value='S'>Small</option>
                    <option value='M'>Medium</option>
                    <option value='L'>Large</option>
                    <option value='XL'>Extra Large</option>
                </select>
            </td>
        </tr>
        <tr>
            <td rowspan="2">
                <label>Dietary Requirements</label>
            </td>
            <td rowspan="2">
                <div>
                    <form action=''>
                        <input type='radio' name='diet' id='dietnone'>None</input>
                        <input type='radio' name='diet' id='dietveg'>Vegetarian</input>
                        <input type='radio' name='diet' id='dietvegan'>Vegan</input>
                        <input type='radio' name='diet' id='other'>Other</input>
                    </form>
                </div>
                <div>
                    <textarea rows=5 id='dietmessage' placeholder='<?= $dietMessage ?>'></textarea>
                </div>
            </td>
        </tr>
        <tr>
        </tr>
        <tr>
            <td id='submit' colspan='2'>
                <button onclick='submit()'>Submit</button>
            </td>
        </tr>
    </table>
</div>
<?php else:?>

<div id='formDiv'>
    <h3><?= $hackathonEvent->hackathonEventName ?> Team Sign Up Page</h3>
    <p>You have already signed up for the hackathon</p>
    <button onclick='window.location.href="/hackathon/person/signup"'>Individual Sign Up</button>
</div>

<?php endif;?>