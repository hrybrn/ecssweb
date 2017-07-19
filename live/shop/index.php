<?php
$relPath = "../";

include_once($relPath . "navbar/navbar.php");
echo getNavBar();

?>

<script src="shop.js"></script>

<script>

$(document).ready(function(){
    getItems("");
});

</script>
