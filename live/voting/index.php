<?php
$relPath = "../";
include_once ($relPath . 'includes/setLang.php');

$dbLoc = realpath($relPath . "../db/ecss.db");

$db = new PDO('sqlite:' . $dbLoc);

require_once($relPath . '../config/config.php');

require_once($relPath . "voting/youtubePlaylists.php");

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

if($userInfo['username'] != 'hb15g16' & $userInfo['username'] != 'jc14g16'){
    echo "Sorry! We are still testing this site!";
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
    <title>Voting | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrftoken" content="<?= $csrftoken ?>">
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

$res = $db->query($sql);
$testResult = $res->fetchObject();

if(!$testResult){
    echo '<link rel="stylesheet" type="text/css" href="/voting/vote.css" />';

    //get electionID
    $sql = "SELECT e.electionID AS electionID
    		FROM election AS e
    		WHERE datetime(e.votingStartDate) < datetime('now')
    		AND datetime(e.votingEndDate) > datetime('now');";

    $statement = $db->query($sql);

    if($electionID = $statement->fetchObject()){
        $electionID = $electionID->electionID;

        //no current nomination phase, checking for voting phase
    	$sql = "SELECT
                    e.electionID,
                    e.electionTypeID,
                    p.positionID,
                    p.positionName,
                    p.description,
                    n.nominationName,
                    n.manifesto,
                    n.image,
                    n.nominationID
                FROM ((election AS e
                INNER JOIN position AS p
                ON e.electionTypeID = p.electionTypeID)
                LEFT JOIN nomination AS n
                ON p.positionID = n.positionID)
                LEFT JOIN (
                    SELECT *
                    FROM voted AS v
                    WHERE v.electionID = :electionID
                    AND v.voteHash = :hash) AS v
                ON p.positionID = v.positionID
                WHERE e.electionID = :electionID
                AND v.voteHash IS NULL;";

    	$voting = true;

        $hash = hash('sha256', $userInfo['username']);

        $res = $db->prepare($sql);
        $res->execute([':hash' => $hash, ':electionID' => $electionID]);
        $testResult = $res->fetchObject();
        if(!$testResult){
            echo "<h3 id='errorMessage'>You have already voted for every position in this election, sorry!</h3>";
            exit;
        }
    } else {
        $testResult = false;
    }


	if(!$testResult){
		//no current election is happening

		echo "<h3 id='errorMessage'>No election is taking place currently, Sorry!</h3>";
		exit;
	}
}
$election = [$testResult];
//$res is now the db result object
//get the other election objects
while($row = $res->fetchObject()){
	$election[] = $row;
}

//voting page
if($voting){
	echo "<script src='/voting/vote.js'></script>";
	echo "<script src='/jquery-ui.js'></script>";
	echo '<script src="/jquery.ui.touch-punch.js"></script>';
	echo '<link rel="stylesheet" type="text/css" href="/jquery-ui.css" />';

	// auth status
    echo '<p class="authStatus">Hello, ' . $userInfo['username'] .' <a href="' . $relPath . 'auth/logout.php" class="button">Logout</a></p>';

	//create button div
	$buttonDiv = "<div id='buttonDiv' class='buttonDiv'>";
	$used = [];
	foreach($election as $nomination){
		if(!in_array($nomination->positionName, $used)){
			if(!isset($first)){
				$first = "button" . $nomination->positionID;
			}

			$used[] = $nomination->positionName;
			$buttonDiv .= "<button data-positionid='" . $nomination->positionID . "' id='button" . $nomination->positionID . "' onclick='showPosition(id)'>" . $nomination->positionName . "</button>";
		}
	}

	$buttonDiv .= "</div>";

	echo "<p class='intro'>Hi there! Welcome to our annual general election. Over the past few weeks your peers have been nominating themselves for positions and here are their manifestos. Please check out their speeches below to learn more about the people you are voting onto ECSS committee. Voting closes on Friday April 20th, 2018 18:00.</p>";

  //youtube embed
  echo '<div style="text-align: center;"><iframe id="playlist" width="560" height="315" src="https://www.youtube.com/embed/videoseries?list=PL7QE45LzlPZ65c4kAAtPLqxWwQCIl5emz" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div>';

  echo $buttonDiv;
  echo "<div style='text-align: center;'><button id='submit' onclick='submit()'>Submit vote for this role</button></div>";
  echo "
		<script>
			var first = '" . $first . "';
            var youtube = " . json_encode($youtube) . ";
		</script>";
}
//nomination page
else {
	echo "<script src='/voting/nominate.js'></script>";
	echo "<script src='/ajaxfileupload.js'></script>";

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

	echo "<div id='nominationDescription'></div>";

	echo "
		<script>
			var first = " . $first . ";
			var userInfo = " . json_encode($userInfo) . ";
		</script>";
}
?>
</body>
</html>
