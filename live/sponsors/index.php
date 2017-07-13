<!doctype html>
<html>
<head>
<?php
$relPath = "../";

include_once($relPath . 'navbar/navbar.php');
echo getNavBar();


$raw = file_get_contents($relPath . "../data/sponsors.json");
$sponsors = json_decode($raw, true);

$gold = "";
$silver = "";
$bronze = "";

$i = 0;

foreach ($sponsors['Gold'] as $sponsor => $data) {
    $gold .= '<button class="gold" onclick="showMember(' . $i . ')" id="button' . $i++ . '">' . $sponsor . '</button>';
}

foreach ($sponsors['Silver'] as $sponsor => $data) {
    $gold .= '<button class="silver" onclick="showMember(' . $i . ')" id="button' . $i++ . '">' . $sponsor . '</button>';
}

foreach ($sponsors['Bronze'] as $sponsor => $data) {
    $gold .= '<button class="bronze" onclick="showMember(' . $i . ')" id="button' . $i++ . '">' . $sponsor . '</button>';
}
?>

<link rel="stylesheet" href="<?= $relPath ?>theme.css">

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sponsors | ECSS</title>

<script> var relPath = "<?= $relPath ?>";</script>
<script src="<?=$relPath ?>sponsors/sponsors.js"></script>
</head>
<body>
<!--
<br>
        <table class="wholeTable">
        <tr>
            <td rowspan="2"><img id="sponsorImage" class="pageImage"/></td>
            <td><?= $gold; $silver; $bronze ?></td>
        </tr>
        <tr>
            <td>
                <table id="sponsorTable" class="pageTable"></table>
            </td>
    </table>
-->
<div>
    <div id="sponsorImageContainer"><img id="sponsorImage" class="pageImage"/></div>
    <div id="sponsorButtonGroup"><?= $gold; $silver; $bronze ?></div>
    <table id="sponsorTable" class="pageTable"></table>
</div>

<script>
    $(document).ready(function () {
        showMember("0");
    });
</script>
</body>
</html>