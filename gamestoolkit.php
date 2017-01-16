<?php
function apiUpdate($games){
	//this doesn't actually update api stuff yet, just the timestamp
	$return = (array)$games;
	$keys = array_keys($return);
	$time = time();
	$timestamp = 0;
	if (($key = array_search("timestamp", $keys)) !== false){	
		unset($keys[$key]);
		$timestamp = $return[$key];
	}
	if ($time - $timestamp > 1800){
		//insert api updates here
		$return["timestamp"] = $time;
	}
	return (object)$return;
}
function addGame($games, $name, $level){
	$return = (array)$games;
	$return[$name] = $level;
	return (object)$return;
}
?>