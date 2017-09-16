<?php

$changes = $_POST['changes'];
$groupID = $_POST['groupID'];
$time = new DateTime($_POST['time']);

$relPath = "../../";

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

foreach($changes as $taskID => $newValue) {
	echo $newValue;
	if(trim($newValue) != ""){
		//set previous entries to latest = 0
		$sql = "UPDATE taskEntry
				SET latest = 0
				WHERE groupID = :groupID
				AND taskID = :taskID
				AND latest = 1;";

		$statement = $db->prepare($sql);
		$statement->execute(array(':groupID' => $groupID, ':taskID' => $taskID));

		//save it!!
		$sql = "INSERT INTO taskEntry(groupID, taskID, entry, latest, entryTime)
				VALUES(:groupID, :taskID, :entry, :latest, :entryTime);";

		$statement = $db->prepare($sql);
		$statement->execute(array(
			':groupID' => $groupID,
			':taskID' => $taskID,
			':entry' => $newValue,
			':latest' => 1,
			':entryTime' => $time->format(DateTime::RFC1036)
		));
	}
}

$result = array('status' => 'true');

echo json_encode($result);