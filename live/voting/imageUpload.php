<?php
$relPath = "../";

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

$target_dir = $relPath . "images/nominations/";

if(!file_exists($target_dir)){
	mkdir($target_dir);
}

$nominationID = (int)$_POST['nominationID'];

require_once($relPath . '../config/config.php');

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