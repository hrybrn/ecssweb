<?php

$name = $_GET["name"];
$name = str_replace("%20", " ", $name);

$relPath = "../";

$raw = file_get_contents($relPath . "data/sponsors.json");
$sponsors = json_decode($raw, true);


foreach($sponsors as $type => $sponsor) {
    if($sponsor['Name'] == $name) {
        echo json_encode ($sponsor);
        return;
    }
}