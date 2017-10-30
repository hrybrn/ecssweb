<?php
$relPath = "../";

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

// csrf token -- this protects us from sophisticated authentication faking
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

//if you aren't in ECS, this is where we kick you out!
if(!(in_array("fpStudent", $userInfo['groups']) || in_array("fpStaff", $userInfo['groups']))){
	echo "You're not a member of ECS";
	exit;
}

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
<div id='formDiv' style='display: block; text-align: center; width: 90%; margin-left: 5%;'>
    <p>
    ECSS wants to hear feedback from you guys on what we do well, and what we could do better on.
    Any data you submit in this form is anonymous and cannot be traced back to you.
    </p>
    <p style='font-weight: bold;'>
    This is assuming you don't name drop yourself, or give your identity away within it.
    </p>
    <p>
    All source code is available on the <a href='https://github.com/hrybrn/ecssweb/tree/master/live/comment'>Github Repo</a>, and is documented with what everything does.
    </p>
    <p>
    If you have any suggestions of how to improve this page or the site in general please submit them here as well!
    </p>

    <div>
    <textarea rows="6" cols="50" id='comment'></textarea>
    </div>

    <button id='submit' onclick='submit()'>Submit</button>
</div>

<script>
//this small function submits your comment
function submit(){
    var saveData = {};
    saveData.comment = $('#comment').val();

    $.ajax({
        url: '/comment/save.php',
        type: 'get',
        data: saveData,
        dataType: 'json',
        success: function (response) {
            $('#submit').replaceWith("<p>" + response.message + "</p>");
        }
    });
}
</script>