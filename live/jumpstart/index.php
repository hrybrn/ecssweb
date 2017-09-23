<?php
$relPath = "../";
include_once($relPath . "includes/setLang.php");

$locationFile = fopen($relPath . "../data/mapLocations.csv", 'r');
$locations = array();

while($line = fgetcsv($locationFile)){
    //fix for excel adding blank lines at the bottom
    if($line[0] != ""){
        $locations[] = new Location(trim($line[0]), trim($line[1]), trim($line[2]), trim($line[3]), trim($line[4]));
    }
}

fclose($locationFile);

class Location {
    public $label;
    public $description;
    public $lat;
    public $lng;
    public $cityChallenge;

    public function __construct($label, $description, $lat, $lng, $cityChallenge){
        $this->label = $label;
        $this->description = $description;
        $this->lat = floatval($lat);
        $this->lng = floatval($lng);

        if($cityChallenge == "true"){
            $this->cityChallenge = $cityChallenge;
        }
    }
}

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Jumpstart 2017 | ECSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="/theme.css" />
    <link rel="stylesheet" type="text/css" href="/jumpstart/jumpstart.css" />
    <script src="/jumpstart/jumpstart.js"></script>
    <script src="/jquery.js"></script>
</head>
<body>
<?php
include_once($relPath . "navbar/navbar.php");
echo getNavBar();
?>
<div class="pageContainer">
    <div><p>
        Jumpstart is an opportunity for freshers to meet other students in the faculty, take part in a range of activities, and settle into Southampton. It is the first event of the year organised by ECSS, and is sponsored by IBM. See below for the week’s timetable, and information on the City Challenge which is part of the UG timetable. If you have any questions feel free to <a href="/about/contact.php?lang=<?= $lang ?>">contact our committee</a>, and have fun!
    </p></div>
    <div id="jumpstartLogoContainer" class="logoContainer"><img src="/images/jumpstart/jumpstart_logo.png" alt="Jumpstart logo"></div>
    <div id="ibmLogoContainer" class="logoContainer"><p>Proud Sponsor</p><img src="/images/jumpstart/jumpstart_sponsor_ibm.jpg" alt="Jumpstart sponsor IBM logo"></div>
    <div id="jumpstartLinksContainer">
        <ul id="jumpstartLinks">
            <li><a onclick="toggleCalendar(event)" id='mscTimetable'>Timetable - MSc</a></li>
            <li><a onclick="toggleCalendar(event)" id='ugTimetable'>Timetable - UG</a></li>
            <li><a href="#cityChallenge" onclick="toggleCalendar(event)" id='map'>City Challenge</a></li>
            <li><a href="/jumpstart/group">Jumpstart Groups</a></li>
        </ul>
    </div>
    <div id="cityChallenge">
    <p>
        The Jumpstart City Challenge is our take on introducing you to Southampton. We’ll be splitting you into teams, assigning you a Jumpstart Helper (a current ECS student), and giving you the aim of getting as many points as possible.
    </p>
    <p>
        Points can be achieved by exploring the main areas this side of the city; Highfield Campus, The Common, and Portswood; and completing various challenges outlined below. In doing so, you’ll be introduced to the members of the ECSS committee, find your bearings in Southampton, and make some new friends!
    </p>
    <p>
        The winning team will be announced at the Jumpstart reception on Friday 29th September, with prizes from the faculty, ECSS, and IBM up for grabs. Some of the challenges are listed on the map below with red pins, and some you do whilst on your travels. You can do as many or as few as you like, in any order. Good luck!
    </p>
    </div>
    <div id="mapCalendar" class="centerDiv"></div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsi5_PVb95lyv6OTu6F3kpDiKZa_EgOnM"></script>
<script src="/jquery.js"></script>
<script>
    var mapOptions = {
        center: new google.maps.LatLng(50.9264099,-1.3953199),
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var locations = <?= json_encode($locations) ?>;

    var map = new google.maps.Map(document.getElementById('mapCalendar'), mapOptions);

    var markers = [];

    function makeNameTag(name, description){
        return "<span><h3>" + name + "</h3><p>" + description + "</p></span>";
    }


    $(document).ready(function(){
        google.maps.event.addDomListener(window, 'load');

        $(locations).each(function(index, position){
            var options = {
              position: position,
              map: map,
              title: position.label,
            };

            if(position.cityChallenge){
                options.icon = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
            } else {
                options.icon = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
            }
            
            var marker = new google.maps.Marker(options);

            marker.infowindow = new google.maps.InfoWindow({
                content: makeNameTag(position.label, position.description)
            });


            marker.addListener('click', function(){
                $(markers).each(function(){
                    this.infowindow.close();
                });

                marker.infowindow.open(map, marker);
            });

            markers.push(marker);
        });
    });
</script>
</body>
</html>
