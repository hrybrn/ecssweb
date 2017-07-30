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
    foreach($sponsors as $type => $sponsor) {
        foreach ($sponsor as $title => $data)
            if ($title == $name) {
                $data['Type'] = $type;
                return json_encode($data);
            }
    }
}