<?php
$relPath = "../";

include_once($relPath . "navbar/navbar.php");

if (!isset($_GET['section'])) {
    echo "Not a valid leaflet section!";
    exit;
}
$section = $_GET['section'];

$raw = file_get_contents($relPath . "../data/" . $lang . "/leaflet.json");
$sectionData = json_decode($raw, true);

$sectionData = $sectionData[$section];

$body = "";

//todo: print out title
$body .= "<div id='titleDiv'>";
$body .= "<h1 id='sectionTitle'>" . $section . "</h1>";

foreach ($sectionData as $key => $value) {
    if ($key == "Overview") {
        //todo: print out overview
        $body .= "<p id='overview'>" . $value . "</p>";
        $body .= "</div>";
    } elseif ($key == "Subsections") {
        //$body .= "<p>lolool</p>";
        foreach ($value as $subkey => $subvalue) {
            //$body .= "<p>sub-lolool</p>";
            //todo: print out subsection title
            $body .= "<div class='subsection'>";
            $body .= "<div class='subsectionText'>";
            $body .= "<h1 class='subsectionTitle'>" . $subkey . "</h1>";

            foreach ($subvalue as $subsubkey => $subsubvalue) {
                if ($subsubkey == "Text") {
                    //$body .= "<p>subsub-lolool</p>";
                    //todo: print out text
                    $body .= "<p class='subsectionText'>" . $subsubvalue . "</p>";
                    $body .= "</div>";
                }

                if ($subsubkey == "Image") {
                    //$body .= "<p>sub-image-lolool</p>";
                    //todo: print out image
                    $body .= "<img class='subsectionImage' src='" . $relPath . $subsubvalue . "'>";
                }

                if ($subsubkey == "Slideshow") {
                    $unfiltered = scandir($relPath . $subsubvalue);
                    $files = array();

                    $lower = strtolower($section);

                    foreach ($unfiltered as $file) {
                        if($file != '.' && $file != '..'){
                            $files[] = $relPath . "images/leaflet/" . $lower . "/slideshow/" . $file;
                        }
                    }

                    echo "
                        <script>
                            var files = " . json_encode($files) . ";
                            var section = '" . $section . "';
                        </script>
                        ";

                    $imageClass = "";

                    if($section == "Football"){
                        $imageClass = "subsectionImage";
                    }

                    $body .= "<div class=\"slideshow " . $imageClass . "\">";
                    $body .= "<button class='slideshowButton' onclick='nextPhoto(-1)'>Previous</button><button class='slideshowButton' onclick='nextPhoto(1)'>Next</button></div>";
                }
            }
            
            $body .= "</div>";
        }
    }
}
?>
<html>
    <title><?= $sectionData['title'] ?></title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?= $relPath ?>theme.css"/>
    <link rel="stylesheet" type="text/css" href="<?= $relPath ?>/leaflet/leaflet.css"/>
    <script src="<?=$relPath ?>leaflet/leaflet.js"></script>

    <body>
<?= getNavBar(); ?>
<?= $body ?>
    </body>

    <script>
        $(document).ready(function(){
            if(typeof files !== 'undefined'){
                nextPhoto(0);
            }

            if(section == "Sports"){
                $('#overview').css("float", "left");
            }
        });
    </script>
</html>