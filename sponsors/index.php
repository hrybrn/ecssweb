<?php
$relPath = "../";

include_once($relPath . 'navbar/navbar.php');
echo getNavBar();


$raw = file_get_contents($relPath . "data/sponsors.json");
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

<script> var relPath = "<?= $relPath ?>";</script>
<script src="<?=$relPath ?>sponsors/sponsors.js"></script>

<table>
    <?= $gold ?>
    <?= $silver ?>
    <?= $bronze ?>
</table>

<table id="sponsorTable"></table>
<div id="links"></div>

<script>
    $(document).ready(function () {
        showMember("0");
    });
</script>