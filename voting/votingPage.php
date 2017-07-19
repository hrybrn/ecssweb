<?
if(!isset($relPath)){
    $relPath = "../live";
}

include_once($relPath . "../db/dbConnect.php");

if($setup['phdMasters'] == "disabled")
    $mode = "general";
else
    $mode = "phdMasters";

//including relevant layout
switch($mode){
    case "nominate":
        include_once($relPath . "../voting/nominate.php");
        break;
    case "vote":
        include_once($relPath . "../voting/vote.php");
        break;
    case "results":
        include_once($relPath . "../voting/results.php");
        break;
    default:
        break;
}
