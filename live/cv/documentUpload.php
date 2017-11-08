<?php
exit;
$relPath = "../";

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

$type = $_POST['type'];
$failure = ['status' => false, 'message' => 'Something went wrong with your ' . $type . ' upload. Please email <a href="mailto:hb15g16@soton.ac.uk">Harry Brown</a> to fix this.'];
$success = ['status' => true];

if($type == "Cover"){
	$success['message'] = "Cover letter uploaded successfully.";
} else {
	$success['message'] = 'CV uploaded successfully.';
}


if($type != "CV" && $type != "Cover"){
	echo json_encode($failure);
}

$target_dir = $relPath . "../data/" . $type . "/";

if(!file_exists($target_dir)){
	mkdir($target_dir);
}

$applicationID = (int)$_POST['applicationID'];


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


foreach($_FILES as $task => $file){
	$target_file = $target_dir . basename($file["name"]);
	$uploadOk = true;
	$fileEx = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	$allowed = ['doc', 'docx', 'pdf', 'rtf', 'txt', 'wks', 'wkp', 'wpd'];

	if(!in_array($fileEx, $allowed)){
		$uploadOk = false;
	}

	while (file_exists($target_file) && $uploadOk) {
		$target_file = $target_dir . randomString() . "." . $fileEx;
	}

	if (!$uploadOk) {
		echo json_encode($failure);
		exit;
	} else {
	    if (move_uploaded_file($file["tmp_name"], $target_file)) {
			$sql = "UPDATE application
					SET application" . $type . " = :file
					WHERE applicationID = :applicationID
					AND applicationUsername = :applicationUsername";

			$statement = $db->prepare($sql);
			$statement->execute([':applicationID' => $applicationID, ':applicationUsername' => $userInfo['username'], ':file' => $target_file]);

			echo json_encode($success);
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