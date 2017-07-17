<?
$relPath = "../";

include_once($relPath . "navbar/navbar.php");

$raw = file_get_contents($relPath . "../data/setup.json");
$setup = json_decode($raw, true);

echo getNavBar();

include_once($relPath . "../voting/votingPage.php");
