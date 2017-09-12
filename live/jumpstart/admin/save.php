<?php

$changes = $_GET['changes'];
$groupID = $_GET['groupID'];
$time = new DateTime($_GET['time']);

$relPath = "../../";

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

foreach($changes as $taskID => $newValue) {
	if($newValue != ""){
		//set previous entries to latest = 0
		$sql = "SELECT te.taskEntryID
				FROM taskEntry AS te
				WHERE te.groupID = :groupID
				AND te.taskID = :taskID
				AND te.latest = 1;";

		$statement = $db->prepare($sql);
		$statement->execute(array(':groupID' => $groupID, ':taskID' => $taskID));

		while($latest = $statement->fetchObject()){
			$sql = "UPDATE taskEntry
					SET latest = 0
					WHERE taskEntryID = :taskEntryID;";

			$statement = $db->prepare($sql);
			$statement->execute(array(':taskEntryID' => $latest->taskEntryID));
		}

		//save it!!
		$sql = "INSERT INTO taskEntry(groupID, taskID, entry, latest, entryTime) VALUES(:groupID, :taskID, :entry, :latest, :entryTime);";
		$statement = $db->prepare($sql);
		$statement->execute(array(':groupID' => $groupID, ':taskID' => $taskID, ':entry' => $newValue, ':latest' => 1, ':entryTime' => $time->format(DateTime::RFC1036)));
	}
}

echo "done!!";