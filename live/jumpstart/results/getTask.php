<?php

$taskID = $_GET['taskID'];
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

$sql = "SELECT g.groupID, g.groupName, t.taskID, t.file, te.entry, te.entryTime
		FROM (jumpstartGroup AS g
		LEFT JOIN taskEntry AS te ON g.groupID = te.groupID)
		LEFT JOIN task AS t ON te.taskID = t.taskID
		WHERE te.latest = 1
		AND t.taskID = :taskID;";

$statement = $db->prepare($sql);
$statement->execute(array(':taskID' => $taskID));

$entries = array();
while($entry = $statement->fetchObject()){
	$entries[$entry->groupID] = $entry;

	if(!isset($file)){
		$file = $entry->file;
	}
}

ksort($entries);

$output = "<table><tr><th>Group</th><th>Entry</tr></tr>";

if($file){
	foreach($entries as $entry){
		$time = new DateTime($entry->entryTime);

		$row = "<tr><td><p>" . $entry->groupName . "</p><p>Group " . $entry->groupID . "</p></td>";
		$row .= "<td><p>Submitted " . $time->format('d/m/Y, H:i:s') . "</p><p><img width=400 src='" . $entry->entry . "'</p></td></tr>";

		$output .= $row;
	}
} else {
	foreach($entries as $entry){
		$time = new DateTime($entry->entryTime);

		$row = "<tr><td><p>" . $entry->groupName . "</p><p>Group " . $entry->groupID . "</p></td>";
		$row .= "<td><p>Submitted " . $time->format('d/m/Y, H:i:s') . "</p><p>" . $entry->entry . "</p></td></tr>";

		$output .= $row;
	}
}

$output .= "</table>";
$result['data'] = $output;
echo json_encode($result);