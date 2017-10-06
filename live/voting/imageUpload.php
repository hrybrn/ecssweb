<?php
$relPath = "../";

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

$target_dir = $relPath . "images/nominations/";

if(!file_exists($target_dir)){
	mkdir($target_dir);
}

$nominationID = (int)$_POST['nominationID'];



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
		echo json_encode(['status' => false, 'message' => 'You have nominated yourself but omething went wrong with your image upload. Please email <a href="mailto:hb15g16@soton.ac.uk">Harry Brown</a> to fix this.']);
	} else {
	    if (move_uploaded_file($file["tmp_name"], $target_file)) {

	        image_fix_orientation($target_file);

			$sql = "UPDATE nomination SET image = :imageUrl WHERE nominationID = :nominationID";
			$statement = $db->prepare($sql);
			$statement->execute([':imageUrl' => $target_file, ':nominationID' => $nominationID]);
			echo json_encode(['status' => true, 'message' => 'You have nominated yourself successfully and your image has been uploaded successfully.']);			
	    }
	}
}

function randomString(){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 10; $i++) {
        $randstring .= $characters[rand(0, 51)];
    }
    return $randstring;
}

function image_fix_orientation($filename) {
	$imageFileType = pathinfo($filename,PATHINFO_EXTENSION);
	switch($imageFileType){
		case "jpg":
		case "jpeg":
			$image = imagecreatefromjpeg($filename);
			break;
		case "png":
			$image = imagecreatefrompng($filename);
			break;
		case "gif":
			$image = imagecreatefromgif($filename);
			break;
	}

    $image = imagerotate($image, array_values([0, 0, 0, 180, 0, 0, -90, 0, 90])[@exif_read_data($filename)['Orientation'] ?: 0], 0);

    switch($imageFileType){
		case "jpg":
		case "jpeg":
			imagejpeg($image, $filename);
			break;
		case "png":
			imagepng($image, $filename);
			break;
		case "gif":
			imagegif($image, $filename);
	}

	imagedestroy($image);
}