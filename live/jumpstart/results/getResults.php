<?php

$groupID = $_GET['groupID'];
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

$result = array('status' => true);

//get all task entries
$sql = "SELECT *
		FROM (task AS t
		INNER JOIN taskEntry AS te ON t.taskID = te.taskID)
		INNER JOIN jumpstartGroup AS g ON te.groupID = g.groupID
		WHERE te.latest = 1
		AND te.groupID = :groupID;";

$statement = $db->prepare($sql);
$statement->execute(array(':groupID' => $groupID));

$entries = array();
while($entry = $statement->fetchObject()){
	$entries[$entry->taskID] = $entry;
}

ksort($entries);
$table = "<table id='tasks'><tr><th>Task</th><th>Entry</th></tr>";

foreach($entries as $entry){
	if(isset($entry->entry)){
		$value = $entry->entry;
	} else {
		$value = "";
	}

	$table .= "<tr><td><p>" . $entry->taskName . "</p></td>";
	if((integer)$entry->file && $value != ""){
		$table .= "<td><img width=400 src='" . $value . "'></td></tr>";
	} else {
		$table .= "<td><p>" . $entry->entry . "</p></td></tr>";
	}
}

$result['data'] = $table
;echo json_encode($result);