<?php

function getNavBar() {
    global $relPath;
    
    $nav = "";
    $nav .= '<script src="' . $relPath . 'jquery.js"></script>';
    $nav .= '<link rel="stylesheet" href="' . $relPath . 'navbar/navbar.css">';
    $raw = file_get_contents($relPath . "../data/links.json");
    $links = json_decode($raw, true);
    
    $nav .= '<div class="navbar">';
    $nav .= '<img id="navbarLogo" src="' . $relPath . 'images/ecss-white-logo-minimal.png" height="60">';
    
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
                 <form action="' . $relPath . $address['default'] . '" target="_self">
                 <button id="navButton">' . $name . '</button>
                </form> 
                <div class="dropdown-content">';
             
            foreach($address as $page => $link){
                if($page != 'default') {
                $nav .= '<a href="' . $relPath . $link . '" target="_self">' . $page . '</a>';
                }
            }
            $nav .= '</div></div>';
        } 
        else {
            $nav .= 
                '<form action="'. $relPath . $address . '" target="_self">
                 <button id="navButton">' . $name . '</button>
                 </form>'; 
        }
        
    }
    
    $nav .= '<a href=""><img id="langIcon" src="' . $relPath . '/images/flag-icons/uk.png" height="20"></a>';
    $nav .= '
        <div id="shopButtonDiv">
        <form action="' . relPath . '/shop/">
        <button id="navButton" class="shopButton">Shop</button>
        </form>
        </div>';
    //$nav .= '<img src ="' . $relPath . '/images/fire.gif" height="30px">';
    return $nav . "</div><br>";
}
?>