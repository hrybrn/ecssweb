<?php
$relPath = "../";

include($relPath . "navbar/autonavbar.php");
include($relPath . "auth/forcelogin.php");
include($relPath . "auth/includedb.php");

include($relPath . "auth/committeecheck.php");

setupPage("Slideshow Image Uploader");

$sql = "SELECT s.slideshowID, s.slideshowName
        FROM slideshow AS s";

$statement = $db->query($sql);
$registeredSlideshows = "";

while($slideshow = $statement->fetchObject()){
  $registeredSlideshows .= "<option value='$slideshow->slideshowID'>$slideshow->slideshowName</option>";
}

?>
<form action="/slideshow/upload.php" method='post' enctype="multipart/form-data">
  <select name="slideshowID">
    <?= $registeredSlideshows ?>
  </select>
  Select images: <input type="file" name="images[]" multiple="true">
  <input type='hidden' name='csrftoken' value='<?= $csrftoken ?>'></hidden>
  <input type="submit">
</form>
