<?php

$relPath = "../";

include_once($relPath . "navbar/navbar.php");

if(!isset($_GET['section'])) {
	echo "Not a valid leaflet section!";
	exit;
}
$section = $_GET['section'];

$raw = file_get_contents($relPath . "../data/" . $lang . "/leaflet.json");
$sectionData = json_decode($raw, true);

$sectionData = $sectionData[$section];

$body = "";
for($i = 0; $i < count($sectionData['text']); $i++){
	if(in_array($i, $sectionData['titleIndexes'])){
		$body .= "<h3>" . $sectionData['text'][$i] . "</h3>";
	} else {
		$body .= "<p>" . $sectionData['text'][$i] . "</p>";
	}

	if(isset($sectionData['images'][$i])){
		$body .= "<img class='leafletImage' src='" . $relPath . $sectionData['images'][$i] . "'>";
	}
}
?>
<html>
	<title><?= $sectionData['title'] ?></title>

	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?=$relPath?>theme.css"/>
    <link rel="stylesheet" type="text/css" href="/leaflet/leaflet.css"/>

    <body>
        <?=getNavBar();?>
        <?=$body?>
    </body>
</html>
