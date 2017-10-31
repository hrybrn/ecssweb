<?php

$taskScoreID = $_GET['taskScoreID'];
$scores = $_GET['scores'];
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

$adminID = $user->adminID;

foreach($scores as $groupID => $score){
    $sql = "UPDATE score
            SET latest = 0
            WHERE latest = 1
            AND groupID = :groupID
            AND taskScoreID = :taskScoreID";

    $statement = $db->query($sql);
    $statement->execute(array(
        ':groupID' => $groupID,
        ':taskScoreID' => $taskScoreID
    ));

    $sql = "INSERT INTO score(score, groupID, adminID, taskScoreID, latest)
            VALUES(:score, :groupID, :adminID, :taskScoreID, :latest);";

    $statement = $db->prepare($sql);
    $statement->execute(array(
        'score' => $score,
        'groupID' => $groupID,
        'adminID' => $adminID,
        'taskScoreID' => $taskScoreID,
        'latest' => 1
    ));
}

echo json_encode(array('status' => true));