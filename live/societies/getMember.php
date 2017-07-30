<?php

$name = $_GET["name"];
$lang = $_GET["lang"];

$relPath = "../";

$raw = file_get_contents($relPath . "../data/" . $lang ."/societies.json");
$societies = json_decode($raw, true);

if (!array_key_exists($name, $societies)) { // default if name invalid
    $name = "ECSS";
}

echo json_encode($societies[$name]);