<?php

$relPath = "../";

include_once($relPath . "navbar.php");

echo getNavBar();

$raw = file_get_contents($relPath . "data/committee.json");
$committee = json_decode($raw, true);
