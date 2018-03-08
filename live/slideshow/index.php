<?php
$relPath = "../";

include($relPath . "navbar/autonavbar.php");
include($relPath . "auth/forcelogin.php");
include($relPath . "auth/includedb.php");

//check for committeee
$sql = "SELECT *
        FROM admin AS a
        WHERE a.username = :username";

$statement = $db->prepare($sql);
$statement->execute([':username' => $userInfo['username']]);

if(!$statement->fetchObject()){
  echo "You're not a committee member sorry!";
}

setupPage("Slideshow Image Uploader");

$sql = "SELECT s.slideshowID, s.slideshowName
        FROM slideshow AS s";

$statement = $db->query($sql);
$registeredSlideshows = "";

while($slideshow = $statement->fetchObject()){
  $registeredSlideshows .= "<option value='$slideshow->slideshowID'>$slideshow->slideshowName</option>";
}

?>
<form action="/slideshow/upload.php">
  <select name="slideshowID">
    <?= $registeredSlideshows ?>
  </select>
  Select images: <input type="file" name="img" multiple>
  <input type="submit">
</form>
