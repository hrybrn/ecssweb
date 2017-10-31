<?php

function test(){
    $pdo = new PDO('sqlite:test.db');

    $test = "create table test(test varchar(255));";
    $test2 = "insert into test values('Hello World!');";
    $test3 = "select * from test;";

    $pdo->exec($test);
    $pdo->exec($test2);
    $result = $pdo->query($test3);
    
    return $result->fetchAll();
}

function safe($string){
    return str_replace("'", "''", $string);
}

function ex($sql){
    global $relPath
    $pdo = new PDO('sqlite:' . $relPath . '../db/ecss.db');

    return $pdo->query($sql)->fetchAll();
}
