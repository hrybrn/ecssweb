<?php

include_once ($relPath . 'includes/setLang.php');

$currentURL = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$nakedURL = strtok($currentURL);

function getNavBar() {
    global $relPath;
    global $lang;
    global $nakedURL;

    $nav = "";
    $nav .= '<script src="' . $relPath . 'jquery.js"></script>'; // Javascript
    $nav .= '<script src="/navbar/spin.js"></script>'; //spinning logo
    $nav .= '<link rel="stylesheet" href="' . $relPath . 'navbar/navbar.css">'; // css

    $raw = file_get_contents($relPath . "../data/en/links.json");
    $links = json_decode($raw, true);

    //dont show boat ball ticket unless we should be
    $now = new DateTime();
    $votingStart = new DateTime("2018-04-23 11:00:00");
    if($now < $votingStart){
        unset($links['Boat Ball Tickets']);
    }

    $nav .= '<div class="navbar">';
    //$nav .= '<img id="navbarLogo" src="' . $relPath . 'images/new-logo-white-transparent-ver2.png" height="60">';
    $nav .= '<a href="/"> <img id="navbarLogo" src="' . $relPath . 'images/new-logo-black.png" height="60"></a>';

    /*
     * Every entry in $links contains an element of the navbar (link & address).
     * For elements with a dropdown, the address is an array of elements for the dropdown.
     * Navbar elements are buttons, dropdown elements are <a>s
     */
    setTextDomain('title');
    foreach ($links as $name => $address) {
        if(is_array($address)){
            if($address['default'] == '#'){
                $onclick = " onclick='return false;' ";
            } else {
                $onclick = "";
            }
            if(strpos($address['default'], "lang") === false){
                $nav .= '
                <div class="dropdown">
                <a class="navButton" href="'. $relPath . $address['default'] . '?lang=' . $lang . '"' . $onclick . '>' . _($name) . '</a>
                <div class="dropdown-content">';
            } else {
                $nav .= '
                <div class="dropdown">
                <a class="navButton" href="'. $relPath . $address['default'] . '"' . $onclick . '>' . _($name) . '</a>
                <div class="dropdown-content">';
            }

            unset($address['default']);

            foreach($address as $page => $link){
                if(strpos($link, "lang") === false){
                    $nav .= '<a class="link" href="' . $relPath . $link . '?lang=' . $lang . '">' . _($page) . '</a>';
                } else {
                    $nav .= '<a class="link" href="' . $relPath . $link . '">' . _($page) . '</a>';
                }
            }
            $nav .= '</div></div>';
        }
        else {
            $nav .= '<a class="navButton" href="'. $relPath . $address . '?lang=' . $lang . '">' . _($name) . '</a>';
        }

    }
    $nav .= '<div id="langMenu">';
    //$nav .= '<a href="' . $nakedURL . '?lang=en"><img class="langIcon" id="currentLangIcon" src="' . $relPath . '/images/flag-icons/en.png" height="20"></a>';
    //$nav .= '<div id="innerLangMenu">';

    //DISABLED LANGUAGE ICONS
    /*
    $nav .= '<a href="' . $nakedURL . '?lang=bg" target="_self"><img class="langIcon" id="notCurrentLangIcon" src="' . $relPath . '/images/flag-icons/bg.png" height="20"></a>';
    $nav .= '<a href="' . $nakedURL . '?lang=zh-cn" target="_self"><img class="langIcon" id="notCurrentLangIcon" src="' . $relPath . '/images/flag-icons/zh-cn.png" height="20"></a>';
    */


    $nav .= '</div>';


    //DISABLED SHOP BUTTON
//$nav .= '
    //    <div id="shopButtonDiv">
    //    <form action="">
    //    <button class="navButton" class="shopButton">Shop</button>
    //    </form>
    //    </div>';
    //$nav .= '<img src ="' . $relPath . '/images/fire.gif" height="30px">';
    return $nav . "</div>";
}
?>
