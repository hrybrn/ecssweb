<?php

$relPath = "../";

include_once($relPath . "navbar.php");

echo getNavBar();

$raw = file_get_contents($relPath . "data/committee.json");
$committee = json_decode($raw, true);

$i = 0;
foreach($committee as $member => $data){
    echo '<button onclick="showMember(' . $i . ')" id="button' . $i++ . '">' . $member . '</button>';
}

?>
<script> var relPath = "<?= $relPath ?>"; </script>
<script src='<?= $relPath ?>jquery.js'></script>
<script src="committee.js"></script>

<body>
    <img id="memberImage" width="200"/>
    <table id="memberTable"></table>
</body>