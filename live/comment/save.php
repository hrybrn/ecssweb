<?php
$relPath = "../";

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

// verify csrf token
if (!hash_equals($_SESSION['csrftoken'], $_POST['csrftoken'])) {
    echo json_encode(['status' => false, 'message' => "Authorization failed."]);
    exit();
}

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

//deliberately throw your data away
unset($userInfo);
unset($attributes);

//after comments suggesting that we dont hash on username, we now hash on incoming ip
//now that you are authenticated and the database is connected, we can now generate your hash
$date = new Datetime();
$date = $date->format('Y-m-d');
$hash = $_SERVER['REMOTE_ADDR'] . $date;



//we hash your username plus the date 100 times over to make the solving difficulty a bit harder
for($i = 0; $i < 100; $i++){
    $hash = hash("sha512", $hash);
}

//here is where we check for spam
//if you want to see our table structure you can look at https://github.com/hrybrn/ecssweb/blob/master/db/table/comments.sql

//first we delete all hashes older than one day
$sql = "DELETE FROM commentHash
        WHERE datetime(commentHashDate) - datetime('now') > 1";

$db->query($sql);

//now we check for max entries today
$sql = "SELECT count(*) AS count
        FROM commentHash AS ch
        WHERE ch.commentHash = :ourHash;";

$statement = $db->prepare($sql);
$statement->execute([':ourHash' => $hash]);

$result = $statement->fetchObject();

//return an error if there have been more than 5 comments in the past day
if($result->count >= 10){
    echo json_encode(['status' => false, 'message' => 'You have already made 5 comments in the last 24 hours. Please submit your comment later.']);
    exit;
}

//we now save your hash
$sql = "INSERT INTO commentHash(commentHash, commentHashDate) VALUES(:ourHash, :ourDate);";
$statement = $db->prepare($sql);
$statement->execute([':ourHash' => $hash, ':ourDate' => $date]);

//now we save the comment
$comment = $_POST['comment'];
$sql = "INSERT INTO comment(commentMessage) VALUES(:comment);";

$statement = $db->prepare($sql);
$statement->execute([':comment' => $comment]);

//return success
echo json_encode(['status' => true, 'message' => 'Your comment has been saved anonymously and is being looked at by our committee.']);