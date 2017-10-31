<?php

$relPath = "../../";

include_once ($relPath . 'includes/setLang.php');

include_once ($relPath . "navbar/navbar.php");

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    setTextDomain('title');
    ?>
    <title><?= _('Jumpstart') ?> | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?= $relPath ?>theme.css" />
    <link rel="stylesheet" type="text/css" href="map.css"/>
</head>
<body>
<?= getNavBar(); ?>

<!--google map goes here-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsi5_PVb95lyv6OTu6F3kpDiKZa_EgOnM"></script>
<div id='map'></div>

<script>
	var mapOptions = {
	    center: new google.maps.LatLng(37.7831,-122.4039),
	    zoom: 12,
	    mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	new google.maps.Map(document.getElementById('map'), mapOptions);
</script>
</body>
</html>