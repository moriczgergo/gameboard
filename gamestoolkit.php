<?php
include_once "steamtoolkit.php";
include_once "config.php";

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
		foreach ($keys as $key) {
			//insert api updates here
			//Steam API update - START
			if ($key != "id_steam"){
				if (preg_match('/\d+_steam/', $key)){
					$gamename = substr($key, 0, strlen($key) - strlen("_steam"));
					$gamevalue = getAchievedAchievementsCount($gamename, $return["id_steam"], $steam); //TO-DO: check if $return["id_steam"] exists
					if ($gamevalue == -1){
						if ($steam === NULL){
							printErrorPage("An unknown error happened when handling JSON received from the Steam servers. Appid: " . $gamename . ", SteamID: " . $return["id_steam"] . ", Count: ". $gamevalue . ", Steam is NULL.");
						} else {
							printErrorPage("An unknown error happened when handling JSON received from the Steam servers. Appid: " . $gamename . ", SteamID: " . $return["id_steam"] . ", Count: ". $gamevalue . ", Steam is not NULL.");
						}
						die();
					} elseif ($gamevalue == -2){
						if ($steam === NULL){
							printErrorPage("We couldn't access a game that you own. Appid: " . $gamename . ", SteamID: " . $return["id_steam"] . ", Count: ". $gamevalue . ", Steam is NULL.");
						} else {
							printErrorPage("We couldn't access a game that you own. Appid: " . $gamename . ", SteamID: " . $return["id_steam"] . ", Count: ". $gamevalue . ", Steam is not NULL.");
						}
						die();
					}
					$return[$key] = $gamevalue;
				}
			}
			//Steam API update - END
		}
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