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
    echo json_encode(["status" => false, "message" => "user " . $username . " doesn't have permissions for this page"]);
    exit;
}

$electionID = $_GET['electionID'];

$sql = "SELECT *
        FROM election AS e
        INNER JOIN electionType AS et
        ON e.electionTypeID = et.electionTypeID
        WHERE datetime(e.votingEndDate) < datetime('now')
        AND e.electionID = :electionID;";

$statement = $db->prepare($sql);
$statement->execute([':electionID']);
if(!($statement->fetchObject())){
    echo json_encode(["status" => false, "message" => "This election has not finished yet! wait a bit!"]);
    exit;
}

$sql = "SELECT *
        FROM election AS e
        INNER JOIN position AS p
        ON e.electionTypeID = p.electionTypeID
        WHERE e.electionID = :electionID;";

$statement = $db->prepare($sql);
$statement->execute([':electionID' => $electionID]);
$positions = [];

while($position = $statement->fetchObject()){
    $positions[] = $position;
}

$standings = [];
foreach($positions as $position){
    $sql = "SELECT count(*) AS count
        FROM vote AS v
        INNER JOIN nomination AS n
        ON v.nominationID = n.nominationID
        WHERE v.ranking = 0
        AND n.electionID = :electionID
        AND n.positionID = :positionID;";

    $statement = $db->prepare($sql);
    $statement->execute([':electionID' => $electionID, ':positionID' => $position->positionID]);

    if (!$numberOfVotes = $statement->fetchObject()){
        echo json_encode(["status" => false, "message" => "no votes in this election"]);
        exit;
    }

    $numberOfVotes = $numberOfVotes->count;

    $sql = "SELECT n.nominationName, count(*) AS count
            from vote as v
            inner join nomination as n
            on v.nominationID = n.nominationID
            where v.ranking = 0
            and n.positionID = :positionID
            group by v.nominationID
            order by count desc;";

    $statement = $db->prepare($sql);
    $statement->execute([':positionID' => $position->positionID]);

    $section = [];
    while($row = $statement->fetchObject()){
        $row->percentage = ($row->count / $numberOfVotes * 100);
        $row->percentage = number_format((float)$row->percentage, 2, '.', '') . "%";  
        $section[] = $row;
    }

    $standings[$position->positionName] = $section;
}

echo json_encode(["status" => true, "standings" => $standings]);