<?php

include_once ($relPath . 'includes/setLang.php');

$currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$nakedURL = strtok($currentURL);

function getNavBar() {
    global $relPath;
    global $lang;
    global $nakedURL;
    
    $nav = "";
    $nav .= '<script src="' . $relPath . 'jquery.js"></script>';
    $nav .= '<link rel="stylesheet" href="' . $relPath . 'navbar/navbar.css">';
    $raw = file_get_contents($relPath . "../data/" . $lang . "/links.json");
    $links = json_decode($raw, true);
    
    $nav .= '<div class="navbar">';
    //$nav .= '<img id="navbarLogo" src="' . $relPath . 'images/new-logo-white-transparent-ver2.png" height="60">';
    $nav .= '<img id="navbarLogo" src="' . $relPath . 'images/ecss-oldlogo.png" height="60">';
    
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
                 <a id="navButton" href="'. $relPath . $address['default'] . '?lang=' . $lang . '" target="_self">' . $name . '</a>
                 
                <div class="dropdown-content">';
             
            unset($address['default']);
            
            foreach($address as $page => $link){
                $nav .= '<a class="link" href="' . $relPath . $link . '?lang=' . $lang . '" target="_self">' . $page . '</a>';
            }
            $nav .= '</div></div>';
        } 
        else {
            $nav .= 
                '<a id="navButton" href="'. $relPath . $address . '?lang=' . $lang . '" target="_self">' . $name . '</a>'; 
        }
        
    }
    $nav .= '<div id="langMenu">';
    $nav .= '<a href="' . $nakedURL . '?lang=en"" target="_self"><img class="langIcon" id="currentLangIcon" src="' . $relPath . '/images/flag-icons/en.png" height="20"></a>';
    //$nav .= '<div id="innerLangMenu">';
    
    //DISABLED LANGUAGE ICONS
    
    $nav .= '<a href="' . $nakedURL . '?lang=bg" target="_self"><img class="langIcon" id="notCurrentLangIcon" src="' . $relPath . '/images/flag-icons/bg.png" height="20"></a>';
    $nav .= '<a href="' . $nakedURL . '?lang=zh-cn" target="_self"><img class="langIcon" id="notCurrentLangIcon" src="' . $relPath . '/images/flag-icons/zh-cn.png" height="20"></a>';
     
    
    
    $nav .= '</div>';
    
    
    //DISABLED SHOP BUTTON
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
