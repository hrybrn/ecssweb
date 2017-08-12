<?php

function check($votingType) {
	global $relPath;
	$raw = file_get_contents($relPath . "../data/setup.json");
	$setup = json_decode($raw, true);

	$type = false;
	foreach($setup as $key => $value){
		if($value == $votingType){
			$type = $key;
		}
	}

	return $type;
}