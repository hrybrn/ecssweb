<?php

function getNavBar() {
    global $relPath;
    $nav = '<script src="' . $relPath . 'navbar.js"></script>';
    $nav .= '<link rel="stylesheet" href="' . $relPath . 'navbar.css">';
    $raw = file_get_contents($relPath . "data/links.json");
    $links = json_decode($raw, true);
    
    $nav .= '<ul>';

    foreach ($links as $name => $address) {
        if(is_array($address)){
            $nav .= '<div class="dropdown"><ul style="clear:right;">';
            $nav .= '<li><a onclick="showDropdown()">' . $address['name'] . '</a></li>';
            $nav .= '<div id="myDropdown" class="dropdown-content">';
            
            unset($address['name']);
            
            foreach($address as $page => $link){
                $nav .= '<li><a href="' . $relPath . $link . '">' . $page . '</a></li>';
            }
            
            $nav .= '</ul></div></div>';
        }
        else {
            $nav .= '<li><a href="' . $relPath . $address . '">' . $name . '</a></li>';
        }
        
    }
    return $nav . "</ul>";
}
