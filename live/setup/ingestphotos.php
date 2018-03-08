<?php
$relPath = "../";

include($relPath . "auth/forcelogin.php");
include($relPath . "auth/includedb.php");

//only webmaster can run this page
if(!$userInfo['username'] == 'hb15g16'){
  exit;
}

//need directory for slideshow
if(!isset($_GET['directory']) | !isset($_GET['name'])){
  exit;
}

$name = $_GET['name'];
$directory = $_GET['directory'];

//read files in directory
$files = scandir($relPath . "images/" . $directory);

//remove directory files
unset($files[array_search(".", $files)]);
unset($files[array_search("..", $files)]);

$slideshowEntry = "INSERT INTO slideshow(slideshowName, slideshowLocation) VALUES ('$name', '$directory');";
$db->query($slideshowEntry);
$sql = "SELECT slideshowID FROM slideshow ORDER BY slideshowID DESC;";

$statement = $db->query($sql);
$id = $statement->fetchObject();
$id = $id->slideshowID;

$fileEntries = [];
foreach($files as $file) {
  $fileEntries[] = "INSERT INTO slideshowImage(slideshowID, slideshowImageName, activated) VALUES ($id, '$file', 1);";
}

foreach($fileEntries as $fileEntry){
  $db->query($fileEntry);
}
