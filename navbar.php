<?php

function getNavBar() {
    $nav = '<link rel="stylesheet" href="navbar.css"><ul>';
    $raw = file_get_contents("data/links.json");
    $links = json_decode($raw, true);

    foreach ($links as $name => $address) {
        $nav .= '<li><a href="' . $address . '">' . $name . '</a></li>';
    }
    return $nav . "</ul>";
}
