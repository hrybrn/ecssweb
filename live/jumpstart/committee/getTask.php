<?php

$taskScoreID = $_GET['taskScoreID'];
$relPath = "../../";

require_once($relPath . '../config/config.php');

include_once ($relPath . 'includes/setLang.php');

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

include_once($relPath . "navbar/navbar.php");

if (DEBUG) {
    //debug version
    $username = "hb15g16";
} else {
    //live version
    require_once('/var/www/auth/lib/_autoload.php');
    $as = new SimpleSAML_Auth_Simple('default-sp');
    $as->requireAuth();
    $attributes = $as->getAttributes();
    $username = $attributes["http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname"][0];
}

$sql = "SELECT a.adminID
		FROM admin AS a
		WHERE a.username = :username;";

$statement = $db->prepare($sql);
$statement->execute(array(':username' => $username));

if(!$user = $statement->fetchObject()){
	echo json_encode(array('status' => false));
	exit;
}

$sql = "SELECT g.groupID, g.groupName, s.score
        FROM jumpstartGroup AS g
        LEFT JOIN (SELECT * FROM score WHERE taskScoreID = :taskScoreID AND latest = 1) AS s
        ON g.groupID = s.groupID;";

$statement = $db->query($sql);
$statement->execute(array(':taskScoreID' => $taskScoreID));

$scores = array();
while($score = $statement->fetchObject()){
    $scores[$score->groupID] = $score;
}

ksort($scores);

$output = "<table id='scores'><tr><th>Task</th><th>Score</th></tr>";

foreach($scores as $score){
    if(isset($score->score)){
        $value = $score->score;
    } else {
        $value = "";
    }
    $row = "<tr><td>" . $score->groupName . " (Group " . $score->groupID . ")</td><td><input type='text' class='score' data-groupid='" . $score->groupID . "' placeholder='" . $value . "''></td></tr>";

    $output .= $row; 
}

echo json_encode(array('status' => true, 'data' => $output));