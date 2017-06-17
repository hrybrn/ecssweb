<?php

function getNavBar() {
    global $relPath;
    $nav = '<script src="' . $relPath . 'navbar.js"></script>';
    $nav .= '<script src="' . $relPath . 'jquery.js"></script>';
    $nav .= '<link rel="stylesheet" href="' . $relPath . 'navbar.css">';
    $raw = file_get_contents($relPath . "data/links.json");
    $links = json_decode($raw, true);
    
    $nav .= '<div class="navbar">';
    
    $nav .= 
     '<div class="dropdown">
  <a class="dropbtn">Dropdown</a>
  <div class="dropdown-content">
    <a href="#">Link 1</a>
    <a href="#">Link 2</a>
    <a href="#">Link 3</a>
  </div>
</div> ';
    
    //$nav .= '<ul class="mainNav">';
    
    //$child = '<div id="child" hidden="true"><ul class="childNav">';
    
    foreach ($links as $name => $address) {
        if(is_array($address)){
             $nav .= 
                '
                 <div class="dropdown">
                 <form action="' . $relPath . $address . '">
                 <button class="navButton">' . $name . '</button>
                </form> 
                <div class="dropdown-content">';
             
            foreach($address as $page => $link){
                $nav .= '<a href="' . $relPath . $link . '">' . $page . '</a>';
            }
            $nav .= '</div></div>';
        } 
        else {
            $nav .= 
                '<form action="'. $relPath . $address . '">
                 <button class="navButton">' . $name . '</button>
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
