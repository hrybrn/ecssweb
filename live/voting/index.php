<?php
$relPath = "../";

$raw = file_get_contents($relPath . "../data/setup.json");
$setup = json_decode($raw, true);

if($setup['phdMasters'] == "disabled")
    $mode = "general";
else
    $mode = "phdMasters";

$mode = $setup[$mode];

//including relevant layout
header( 'Location: http://society.ecs.soton.ac.uk/voting/' . $mode . '.php' ) ;