<?php

$relPath = "../";

include_once($relPath . "navbar.php");

echo getNavBar();

$raw = file_get_contents($relPath . "data/societies.json");
$societies = json_decode($raw, true);

$i = 0; 
foreach($societies as $society => $data){
    echo '<button onclick="showMember(' . $i . ')" id="button' . $i++ . '">' . $society . '</button>';
}

?>
<script> var relPath = "<?= $relPath ?>"; </script>
<script src='<?= $relPath ?>jquery.js'></script>
<script src="societies.js"></script>
<link rel="stylesheet" href="../theme.css">
<body>
    <br>
    <img id="societyImage" width="200"/>
    <table id="societyTable"></table>
</body>