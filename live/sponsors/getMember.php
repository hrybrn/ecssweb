<?php

$name = $_GET["name"];
$lang = $_GET["lang"];


$name = str_replace("%20", " ", $name);

$relPath = "../";

$raw = file_get_contents($relPath . "../data/" . $lang ."/sponsors.json");
$sponsors = json_decode($raw, true);

$data = assembleData();
if (strcmp($data, "") === 0) { // default if name invalid
    $name = "J. P. Morgan";
    $data = assembleData();
}
echo $data;

function assembleData() {
    global $name;
    global $sponsors;
    global $relPath;

    foreach($sponsors as $type => $sponsor) {
        foreach ($sponsor as $title => $data)
            if ($title == $name) {
                $data['Type'] = $type;

                //find files for slideshow
                if(isset($data['Slideshow'])){
                    $data['Files'] = scandir($relPath . 'images/' . $data['Slideshow']);

                    if (($key = array_search(".", $data['Files'])) !== false) {
                        unset($data['Files'][$key]);
                    }

                    if (($key = array_search("..", $data['Files'])) !== false) {
                        unset($data['Files'][$key]);
                    }
                }

                return json_encode($data);
            }
    }
}

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);

$sql = "INSERT INTO sponsorLog(sponsorName, sponsorLogTime) VALUES(:sponsorName, :sponsorLogTime);";

$time = new DateTime('now');
$time = $time->format('Y-m-d H:i:s');

$statement = $db->prepare($sql);
$statement->execute([
    ":sponsorName" => $name,
    ":sponsorLogTime" => $time
]);