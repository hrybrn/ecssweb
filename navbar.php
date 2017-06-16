<?php

function getNavBar() {
    global $relPath;
    
    $nav = '<link rel="stylesheet" href="' . $relPath . 'navbar.css"><ul>';
    $raw = file_get_contents($relPath . "data/links.json");
    $links = json_decode($raw, true);

    foreach ($links as $name => $address) {
        $nav .= '<li><a href="' . $address . '">' . $name . '</a></li>';
    }
    return $nav . "</ul>";
}
