<!doctype html>
<html>
<head>
<?php

$relPath = "../";
include_once($relPath . "navbar/navbar.php");

echo getNavBar();

$raw = file_get_contents($relPath . "../data/" . $lang . "/committee.json");
$committee = json_decode($raw, true);

$buttons = '<div class="buttonGroup">';

$i = 0; 
foreach($committee as $member => $data){
    $buttons .= '<button onclick="showMember(' . $i . ')" id="button' . $i++ . '">' . $member . '</button>';
}

$buttons .= '</div>';

?>
<script> var relPath = "<?= $relPath ?>"; </script>
<script src='<?= $relPath ?>jquery.js'></script>
<script src="committee.js"></script>
<link rel="stylesheet" href="<?= $relPath ?>theme.css">

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Committee | ECSS</title>
</head>
        
<body>
    <div id="committeePageContainer" class="pageContainer">
        <img id="memberImage" />
        <?= $buttons ?>
        <section id="memberTable"></section>
    </div>

    <script>
        $('#button0').prop("hidden",true);
        $(document).ready( function(){
            showMember("0");
        });
    </script>
</body>
</html>
