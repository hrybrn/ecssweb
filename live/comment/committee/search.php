<?php
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
    echo "user " . $username . " doesn't have permissions for this page";
	exit;
}

$read = $_GET['read'];

if($read == "true"){
    $sql = "SELECT * FROM comment AS c
            LEFT JOIN admin AS a
            ON a.adminID = c.adminID;";
} else {
    $sql = "SELECT * FROM comment AS c
            LEFT JOIN admin AS a
            ON a.adminID = c.adminID
            WHERE c.adminResponse = '';";
}

$comments = [];

$statement = $db->query($sql);
while($comment = $statement->fetchObject()){
    $comments[$comment->commentID] = $comment;
}

echo json_encode($comments);