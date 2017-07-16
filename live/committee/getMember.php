<?php

$name = $_GET["name"];
$lang = $_GET["lang"];
$relPath = "../";


$raw = file_get_contents($relPath . "../data/" . $lang ."/committee.json");
$committee = json_decode($raw, true);

echo json_encode($committee[$name]);