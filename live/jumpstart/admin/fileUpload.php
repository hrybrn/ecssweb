<?php
$relPath = "../../";

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

$target_dir = $relPath . "uploads/";

if(!file_exists($target_dir)){
	mkdir($target_dir);
}

$groupID = $_POST['groupID'];
$time = $_POST['time'];
$hash = $_POST['hash'];

$authenticated = false;
//check that the hash is ok
$sql = "SELECT uh.hash, uh.groupID, uh.expiry
		FROM uploadHash AS uh";

$statement = $db->query($sql);
while($row = $statement->fetchObject()){
	if($row->groupID = $groupID && $row->hash = $hash){
		$expiry = new DateTime($row->expiry);
		$diff = (new DateTime())->diff($expiry);
		if(!$diff->invert){
			$authenticated = true;
			break;
		}
	}
}

if(!$authenticated){
	return;
}

foreach($_FILES as $task => $file){
	$target_file = $target_dir . basename($file["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
	    $check = getimagesize($file["tmp_name"]);
	    if($check !== false) {
	        $uploadOk = 1;
	    } else {
	        $uploadOk = 0;
	    }
	}

	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" ) {
	    $uploadOk = 0;
	}

	while (file_exists($target_file)) {
		$target_file = $target_dir . randomString() . "." . $imageFileType;
	}

	if ($uploadOk == 0) {
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($file["tmp_name"], $target_file)) {
	    	echo $target_file;

	        

			$taskID = str_replace("task", "", $task);

			$sql = "UPDATE taskEntry
					SET latest = 0
					WHERE groupID = :groupID
					AND taskID = :taskID
					AND latest = 1;";

			$statement = $db->prepare($sql);
			$statement->execute(array(':groupID' => $groupID, ':taskID' => $taskID));

			$sql = "INSERT INTO taskEntry(groupID, taskID, entry, latest, entryTime) VALUES (:groupID, :taskID, :entry, :latest, :entryTime)";
			$statement = $db->prepare($sql);
			$statement->execute(array(
				'groupID' => $groupID,
				'taskID' => $taskID,
				'entry' => $target_file,
				'latest' => 1,
				'entryTime' => $time
			));
	    }
	}
}

function randomString()
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 10; $i++) {
        $randstring .= $characters[rand(0, 51)];
    }
    return $randstring;
}