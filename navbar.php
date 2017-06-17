<?php

function getNavBar() {
    global $relPath;
    $nav = '<script src="' . $relPath . 'navbar.js"></script>';
    $nav += '<link rel="stylesheet" href="' . $relPath . 'navbar.css"><ul>';
    $raw = file_get_contents($relPath . "data/links.json");
    $links = json_decode($raw, true);

    foreach ($links as $name => $address) {
        if(is_array($address)){
            $nav .= '<div class="dropdown"';
            $nav .= '<li><a onclick="showDropdown()">' . $address . '</a></li>';
            $nav .= '<div id="myDropdown" class="dropdown-content">';
            
            foreach($address as $page => $link)
            $nav .= '<li><a href="' . $relPath . $address . '">' . $name . '</a></li>';
            
            $nav .= '</div></div>';
        }
        else {
            $nav .= '<li><a href="' . $relPath . $address . '">' . $name . '</a></li>';
        }
        
    }
    return $nav . "</ul>";
}
