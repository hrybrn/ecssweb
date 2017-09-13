<?php
$relPath = "../../";

$target_dir = $relPath . "uploads/";

if(!file_exists($target_dir)){
	mkdir($target_dir);
}

$groupID = $_POST['groupID'];
$time = $_POST['time'];

foreach($_FILES as $task => $file){
	$target_file = $target_dir . basename($file["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
	    $check = getimagesize($file["tmp_name"]);
	    if($check !== false) {
	        echo "File is an image - " . $check["mime"] . ".";
	        $uploadOk = 1;
	    } else {
	        echo "File is not an image.";
	        $uploadOk = 0;
	    }
	}

	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" ) {
	    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	    $uploadOk = 0;
	}

	while (file_exists($target_file)) {
		$target_file = $target_dir . randomString() . "." . $imageFileType;
	}

	if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($file["tmp_name"], $target_file)) {
	        echo "The file ". basename( $file["name"]). " has been uploaded.";

	        $dbLoc = realpath($relPath . "../db/ecss.db");
			$db = new PDO('sqlite:' . $dbLoc);

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
	    } else {
	        echo "Sorry, there was an error uploading your file.";
	    }
	}
}

function randomString()
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 10; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}