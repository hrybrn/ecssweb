<!doctype html>
<html>
<head>
<?php

$relPath = "../";

include_once($relPath . "navbar/navbar.php");

echo getNavBar();

$raw = file_get_contents($relPath . "../data/committee.json");
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
    <!--
    <br>
    <table class="wholeTable">
        <tr>
            <td rowspan="2"><img id="memberImage" class="pageImage"/></td>
            <td><?= $buttons ?></td>
        </tr>
        <tr>
            <td>
                <table id="memberTable" class="pageTable"></table>
            </td>
    </table>
    -->
    <div id="committeePageContainer">
    <img id="memberImage" class="pageImage"/>
    <?= $buttons ?>
    <table id="memberTable" class="pageTable"></table>
    </div>

    <script>
        $('#button0').prop("hidden",true);
        $(document).ready( function(){
            showMember("0");
        });
    </script>
</body>
</html>
