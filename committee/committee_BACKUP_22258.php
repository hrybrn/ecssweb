<<<<<<< HEAD
<?php

$relPath = "../";

include_once($relPath . "navbar/navbar.php");

echo getNavBar();

$raw = file_get_contents($relPath . "data/committee.json");
$committee = json_decode($raw, true);

echo '<div class="buttonGroup">';

$i = 0; 
foreach($committee as $member => $data){
    echo '<button onclick="showMember(' . $i . ')" id="button' . $i++ . '">' . $member . '</button>';
}

echo '</div>';

?>
<script> var relPath = "<?= $relPath ?>"; </script>
<script src='<?= $relPath ?>jquery.js'></script>
<script src="committee.js"></script>
<link rel="stylesheet" href="<?= $relPath ?>theme.css">
<body>
    <br>
    <table>
        <tr>
            <td>
                <img id="memberImage" width="400" style="padding: 5px;"/>
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
=======
<html>
    <head>
        <?php
        $relPath = "../";

        include_once($relPath . "navbar/navbar.php");

        echo getNavBar();

        $raw = file_get_contents($relPath . "data/committee.json");
        $committee = json_decode($raw, true);

        $i = 0;
        foreach ($committee as $member => $data) {
            echo '<button onclick="showMember(' . $i . ')" id="button' . $i++ . '">' . $member . '</button>';
        }
        ?>

        <meta charset="UTF-8">
        <title>Committee | ECSS</title>
        <base target="_blank">
        <link rel="stylesheet" href="theme.css">

        <script> var relPath = "<?= $relPath ?>";</script>
        <script src='<?= $relPath ?>jquery.js'></script>
        <script src="committee.js"></script>
        <link rel="stylesheet" href="<?= $relPath ?>theme.css">
    </head>

    <body>
        <br>
        <img id="memberImage" width="300"/>
        <table id="memberTable"></table>

        <script>
            $(document).ready(function () {
                showMember("0");
            });
        </script>
    </body>
</html>
>>>>>>> fd34da1e27a72fa392f416444a3ba919fb1413c9
