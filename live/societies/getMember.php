<?php

$name = $_GET["name"];
$lang = $_GET["lang"];

$relPath = "../";

$raw = file_get_contents($relPath . "../data/" . $lang ."/societies.json");
$societies = json_decode($raw, true);

echo json_encode($societies[$name]);