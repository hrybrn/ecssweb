<?php

$name = $_GET["name"];

$relPath = "../";

$raw = file_get_contents($relPath . "../data/committee.json");
$committee = json_decode($raw, true);

echo json_encode($committee[$name]);