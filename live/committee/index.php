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
<title>Committee | ECSS</title>
        
<body>
    <br>
    <table>
        <tr>
            <td></td>
            <td><?= $buttons ?></td>
        </tr>
        <tr>
            <td>
                <img id="memberImage" class="pageImage"/>
            </td>
            <td>
                <table id="memberTable"></table>
            </td>
    </table>
    <script>
        $(document).ready( function(){
            showMember("0");
        });
    </script>
</body>
