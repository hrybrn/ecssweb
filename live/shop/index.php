<?php
$relPath = "../";

include_once($relPath . "navbar/navbar.php");
echo getNavBar();

?>

<script src="shop.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $relPath ?>theme.css" />

<script>

$(document).ready(function(){
    getItems("");
});

</script>
