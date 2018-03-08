<?php
if(!isset($relPath)) {
    echo "set the relPath!!";
}

$dbLoc = realpath($relPath . "../db/ecss.db");
$db = new PDO('sqlite:' . $dbLoc);
