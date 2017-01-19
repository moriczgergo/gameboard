<?php
function getSteamLongID($customURL, $steam){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, "http://api.steampowered.com/ISteamUser/ResolveVanityURL/v0001/?key=" . $steam . "&vanityurl=" . preg_replace('/\s+/', '+', $customURL));
	$responseObject = json_decode($content);
	if ($responseObject === NULL) {
		return 1; // response wasn't json
	}
	$responseArray = (array)$responseObject;
	$responseArrayResponseArrray = (array)$responseArray["response"];
	$success = $responseArrayResponseArrray["success"];
	if($success == 42){
		return 2; // user not found
	} elseif ($success == 1) {
		return $responseArrayResponseArrray["steamid"]; // steamid64 value
	} else {
		return 3; // unknown error
	}
}

function getOwnedGames($steamID, $steam){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, "http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/?key=" . $steam . "&steamid=" . $steamID . "&format=json");
	$content = curl_exec($ch);
	$responseObject = json_decode($content);
	if ($responseObject === NULL) {
		return 1; // response wasn't json
	}
	$responseArray = (array)$responseObject;
	$responseArrayResponseArrray = (array)$responseArray["response"];
	$game_count = $responseArrayResponseArrray["game_count"];
	if($game_count == 0){
		return 2; // no games
	}
	$games = (array)$responseArrayResponseArrray["games"];
	$gameidarr = array();
	foreach ($games as $gameobj) {
		$gamearr = (array)$gameobj;
		array_push($gameidarr, $gamearr["appid"]);
	}
	return $gameidarr;
}

function getGameName($appid){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, "http://store.steampowered.com/api/appdetails?appids=" . $appid);
	$content = curl_exec($ch);
	$responseObject = json_decode($content);
	if ($responseObject === NULL) {
		return 1; // response wasn't json
	}
	$foo = (array)$responseObject;
	$bar = (array)array_values($foo)[0];
	$foobar = (array)$bar["data"];
	return $foobar["name"];
}

function getAchievedAchievementsCount($appid, $steamid, $steam){ //NOTE: I needed to use -1 and -2 here, because Steam may send 1 or 2 as response, and error handling would confuse.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, "http://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v0001/?key=" . $steam . "&appid=" . $appid . "&steamid=" . $steamid);
	$content = curl_exec($ch);
	$responseObject = json_decode($content);
	if ($responseObject === NULL) {
		return -1; // response wasn't json
	}
	$responseArray = (array)$responseObject;
	$responseArrayPlayerstatsArray = (array)$responseArray["playerstats"];
	$success = $responseArrayPlayerstatsArray["success"];
	if ($success == false){
		if ($responseArrayPlayerstatsArray["error"] != "Requested app has no stats"){
			return -2; //User doesn't have this game.
		}
	}
	$achievements = $responseArrayPlayerstatsArray["achievements"];
	$achievedAchievements = 0;
	foreach ($achievements as $achievementobj) {
		$achievementarr = (array)$achievementobj;
		if($achievementarr["achieved"] == 1){
			$achievedAchievements++;
		}
	}
	return $achievedAchievements;
}

function isSteamEntry($entry){
	return preg_match('/\d+_steam/', $entry);
}

function entryCutSteam($entry){
	return substr($entry, 0, strlen($entry) - strlen("_steam"));
}
?>
