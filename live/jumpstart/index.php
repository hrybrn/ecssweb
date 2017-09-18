<?php
$relPath = "../";
include_once($relPath . "includes/setLang.php");
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jumpstart 2017 | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/theme.css" />
    <link rel="stylesheet" type="text/css" href="jumpstart.css" />
    <script src="jumpstart.js"></script>
</head>
<body>
<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();
?>
<div class="pageContainer">
    <div><p>Jumpstart is an opportunity for freshers to meet other students in the faculty, take part in a range of activities, and settle into Southampton. It is the first event of the year organised by ECSS, and is sponsored by IBM. See below for the week’s timetable, and information on the City Challenge. If you have any questions feel free to <a href="/about/contact.php?lang=<?= $lang ?>">contact our committee</a>, and have fun!</p></div>
    <div id="jumpstartLogoContainer" class="logoContainer"><img src="/images/jumpstart/jumpstart_logo.png" alt="Jumpstart logo"></div>
    <div id="ibmLogoContainer" class="logoContainer"><p>Sponsor</p><img src="/images/jumpstart/jumpstart_sponsor_ibm.jpg" alt="Jumpstart sponsor IBM logo"></div>
    <div id="jumpstartLinksContainer">
        <ul id="jumpstartLinks">
            <li><a href="#">Timetable - MSc</a></li>
            <li><a href="#">Timetable - UG</a></li>
            <li><a href="#cityChallenge" onclick="scrollToCityChallenge(event)">City Challenge</a></li>
        </ul>
    </div>
    <div id="cityChallenge">
        <p>The Jumpstart City Challenge is our take on introducing you to Southampton. We’ll be splitting you into teams, assigning you a Jumpstart Helper (a current ECS student), and giving you the aim of getting as many points as possible.</p>
        <p>Points can be achieved by exploring the main areas this side of the city; Highfield Campus, The Common, and Portswood; and completing various other challenges outlined below. In doing so, you’ll be introduced to the members of the ECSS committee, find your bearings in Southampton, and make some new friends!</p>
        <p>The winning team will be announced at the Jumpstart reception on Friday 29th September, with prizes from the faculty, ECSS, and IBM up for grabs. The challenges are listed below, you can do as many or as few as you like, in any order. Good luck!</p>
    </div>
    <div id="map"></div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsi5_PVb95lyv6OTu6F3kpDiKZa_EgOnM"></script>
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
