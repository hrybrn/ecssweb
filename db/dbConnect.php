<?php

function test() {
    $pdo = new PDO('sqlite:test.db');

    $test = "create table test(test varchar(255));";
    $test2 = "insert into test values('Hello World!');";
    $test3 = "select * from test;";

    $pdo->exec($test);
    $pdo->exec($test2);
    $result = $pdo->query($test3);
    
    return $result->fetchAll();
}

function safe($sql) {
    return unsafe(sanitise($sql));
}

function unsafe($sql){
    $pdo = new PDO('sqlite:ecss.db');

    return $pdo->query($sql);
}

function sanitise($string){
    return str_replace("'", "''", $string);
}

