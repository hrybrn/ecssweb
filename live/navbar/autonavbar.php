<?php

include($relPath . "navbar/navbar.php");

function setupPage($title){
  global $relPath;
  global $csrftoken;

  echo "
    <!doctype html>
    <html>
    <head>
        <meta charset='utf-8'>";
        setTextDomain('title');
  echo "
        <title>$title | ECSS</title>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <meta name='csrftoken' content='$csrftoken'>
        <link rel='stylesheet' type='text/css' href='" . $relPath . "theme.css' />
    </head>
  ";
  echo getNavBar();
}
