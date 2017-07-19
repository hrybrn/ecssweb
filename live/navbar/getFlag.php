<?php
$relPath = "../";

if(!isset($_GET['lang'])){
    echo $relPath . '/images/flag-icons/uk.png';
}

$lang = $_GET['lang'];

echo $relPath . '/images/flag-icons/' . $lang . '.png';