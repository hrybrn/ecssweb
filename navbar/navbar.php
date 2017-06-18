<?php

function getNavBar() {
    global $relPath;
    $nav = '<script src="' . $relPath . 'navbar.js"></script>';
    $nav .= '<script src="' . $relPath . 'jquery.js"></script>';
    $nav .= '<link rel="stylesheet" href="' . $relPath . 'navbar/navbar.css">';
    $raw = file_get_contents($relPath . "data/links.json");
    $links = json_decode($raw, true);
    
    $nav .= '<div class="navbar">';
    
    
    //$nav .= '<ul class="mainNav">';
    
    //$child = '<div id="child" hidden="true"><ul class="childNav">';
    
    /*
     * Every entry in $links contains an element of the navbar (link & address).
     * For elements with a dropdown, the address is an array of elements for the dropdown.
     * Navbar elements are buttons, dropdown elements are <a>s
     */
    foreach ($links as $name => $address) {
        if(is_array($address)){
             $nav .= 
                '
                 <div class="dropdown">
                 <form action="' . $relPath . $address['default'] . '">
                 <button id="navButton">' . $name . '</button>
                </form> 
                <div class="dropdown-content">';
             
            foreach($address as $page => $link){
                if($page != 'default') {
                $nav .= '<a href="' . $relPath . $link . '">' . $page . '</a>';
                }
            }
            $nav .= '</div></div>';
        } 
        else {
            $nav .= 
                '<form action="'. $relPath . $address . '">
                 <button id="navButton">' . $name . '</button>
                 </form>'; 
        }
        
        /*if(is_array($address)){
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
        */
    }
    //return $nav . "</ul>" . $child;
    return $nav . "</div><br>";
}
