<?php

function getNavBar() {
    global $relPath;
    $nav = '<script src="' . $relPath . 'navbar.js"></script>';
    $nav .= '<script src="' . $relPath . 'jquery.js"></script>';
    $nav .= '<link rel="stylesheet" href="' . $relPath . 'theme.css">';
    $raw = file_get_contents($relPath . "data/links.json");
    $links = json_decode($raw, true);
    
    $nav .= '<ul class="mainNav">';
    
    $child = '<div id="child"><ul class="childNav">';
    
    foreach ($links as $name => $address) {
        if(is_array($address)){
            $nav .= '<li><a onclick="showDropdown()">' . $address['name'] . '</a></li>';
            $nav .= '<div id="myDropdown" class="dropdown-content">';
            
            unset($address['name']);
            
            foreach($address as $page => $link){
                $child .= '<li><a href="' . $relPath . $link . '">' . $page . '</a></li>';
            }
            $child .='</ul></div>';
            $nav .= '</div></div>';
        }
        else {
            $nav .= '<li><a href="' . $relPath . $address . '">' . $name . '</a></li>';
        }
        
    }
    return $nav . "</ul>" . $child;
}
