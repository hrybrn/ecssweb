<?php

if(!isset($_GET['lang'])){
    $lang = "en";
}

else {
    $lang = $_GET['lang'];
}

function getNavBar() {
    global $relPath;
    global $lang;
    
    $nav = "";
    $nav .= '<script src="' . $relPath . 'jquery.js"></script>';
    $nav .= '<link rel="stylesheet" href="' . $relPath . 'navbar/navbar.css">';
    $raw = file_get_contents($relPath . "../data/" . $lang . "/links.json");
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
                 
                 <div id="navButton"><a href="'. $relPath . $address['default'] . '?lang=' . $lang . '">' . $name . '</a></div>
                </form> 
                <div class="dropdown-content">';
             
            unset($address['default']);
            
            foreach($address as $page => $link){
                $nav .= '<a class="link" href="' . $relPath . $link . '?lang=' . $lang . '" target="_self">' . $page . '</a>';
            }
            $nav .= '</div></div>';
        } 
        else {
            $nav .= 
                '<div id="navButton"><a href="'. $relPath . $address['default'] . '?lang=' . $lang . '">' . $name . '</a></div>'; 
        }
        
    }
    
    $nav .= '<a href=""><img id="langIcon" src="' . $relPath . '/images/flag-icons/' . $lang . '.png" height="20"></a>';
    //$nav .= '
    //    <div id="shopButtonDiv">
    //    <form action="">
    //    <button id="navButton" class="shopButton">Shop</button>
    //    </form>
    //    </div>';
    //$nav .= '<img src ="' . $relPath . '/images/fire.gif" height="30px">';
    return $nav . "</div><br>";
}
?>
