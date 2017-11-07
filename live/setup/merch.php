<?php
$relPath = "../";

$dbLoc = realpath($relPath . "../db/ecss.db");

$db = new PDO('sqlite:' . $dbLoc);

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

if($userInfo['username'] != "hb15g16"){
    echo "nice try";
    exit;
}

$db->query("CREATE TABLE item (
    itemID INTEGER PRIMARY KEY AUTOINCREMENT,
    itemName varchar(255),
    itemPrice varchar(255),
    itemDesc varchar(255),
	itemImage varchar(255)
);");

$db->query("CREATE TABLE itemColour (
	colourID INTEGER PRIMARY KEY AUTOINCREMENT,
	itemID INTEGER,
	colourName varchar(255),
	FOREIGN KEY (itemID) REFERENCES item(itemID)
);");

$db->query("CREATE TABLE size (
	sizeID INTEGER PRIMARY KEY AUTOINCREMENT,
	sizeName varchar(255)
);");

$db->query("CREATE TABLE slogan (
    sloganID INTEGER PRIMARY KEY AUTOINCREMENT,
    sloganName varchar(255)
);");

$db->query("CREATE TABLE itemSize (
	itemSizeID INTEGER PRIMARY KEY AUTOINCREMENT,
    sizeID INTEGER,
    itemID INTEGER,
	FOREIGN KEY (itemID) REFERENCES item(itemID),
	FOREIGN KEY (sizeID) REFERENCES size(sizeID)
);");

$db->query("CREATE TABLE itemSlogan (
    itemSloganID INTEGER PRIMARY KEY AUTOINCREMENT,
    sloganID INTEGER,
    itemID INTEGER,
	FOREIGN KEY (itemID) REFERENCES item(itemID),
	FOREIGN KEY (sloganID) REFERENCES slogan(sloganID)
);");

$db->query("CREATE TABLE shop (
	shopID INTEGER PRIMARY KEY AUTOINCREMENT,
	openDate TEXT,
	shutDate TEXT
);");

$db->query("CREATE TABLE collectionDates (
	collectionDateID INTEGER PRIMARY KEY AUTOINCREMENT,
	shopID,
	collectionDate TEXT,
	FOREIGN KEY (shopID) REFERENCES shop(shopID)
);");

$raw = file_get_contents($relPath . "../data/merch.json");
$merchData = json_decode($raw, true);

$toAdd = ['Slogans', 'Sizes'];

foreach ($toAdd as $add){
	$$add = [];
	$dbName = strtolower(substr($add, 0, strlen($add) - 1));
	$sql = "INSERT INTO " . $dbName . "(" . $dbName . "Name) VALUES(:" . $dbName . ");";
	foreach($merchData[$add] as $section){
		$statement = $db->prepare($sql);
	
		$statement->execute([':' . $dbName => $section]);
	
		//get sizeID
		$statement = $db->query("SELECT last_insert_rowid() AS id");
		$insertInfo = $statement->fetchObject();
		$$add[] = $insertInfo->id;
	}
}

//add items
foreach($merchData['Styles'] as $styleName => $style){
	//make item
	$sql = "INSERT INTO item(itemName, itemPrice, itemDesc, itemImage) VALUES(:itemName, :itemPrice, :itemDesc, :itemImage);";

	$statement = $db->prepare($sql);
	$statement->execute([
		":itemName" => $styleName,
		":itemPrice" => $style['Price'],
		":itemDesc" => $style['Description'],
		":itemImage" => $style['Image']
	]);

	//get itemID
	$statement = $db->query("SELECT last_insert_rowid() AS itemID");
	$insertInfo = $statement->fetchObject();
	$itemID = $insertInfo->itemID;

	//add colours
	$sql = "INSERT INTO itemColour(itemID, colourName) VALUES(:itemID, :colourName);";
	foreach($style['Colours'] as $colour){
		$statement = $db->prepare($sql);
		$statement->execute([
			":itemID" => $itemID,
			":colourName" => $colour
		]);
	}

	//add sizes
	if($style['Sizes']){
		$sql = "INSERT INTO itemSize(sizeID, itemID) VALUES(:sizeID, :itemID);";
		
		foreach($Sizes as $sizeID){
			$statement = $db->prepare($sql);
			$statement->execute([
				":sizeID" => $sizeID,
				":itemID" => $itemID
			]);
		}
	}

	//add slogans
	if($style['Slogan']){
		if($style['Slogan'] === true){
			//add all slogans
			$sql = "INSERT INTO itemSlogan(sloganID, itemID) VALUES(:sloganID, :itemID);";

			foreach($Slogans as $sloganID){
				$statement = $db->prepare($sql);
				$statement->execute([
					":sloganID" => $sloganID,
					":itemID" => $itemID
				]);
			}
		} else {
			//slogan is actually the slogan
			$sql = "INSERT INTO slogan(sloganName) VALUES (:slogan);";
			$statement = $db->prepare($sql);
			$statement->execute([
				":slogan" => $style['Slogan']
			]);

			//get sloganID
			$statement = $db->query("SELECT last_insert_rowid() AS id");
			$insertInfo = $statement->fetchObject();

			//connect slogan to item
			$sql = "INSERT INTO itemSlogan(sloganID, itemID) VALUES(:sloganID, :itemID);";
			$statement = $db->prepare($sql);
			$statement->execute([
				":sloganID" => $insertInfo->id,
				":itemID" => $itemID
			]);
		}
	}
}