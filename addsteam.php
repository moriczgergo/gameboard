<?php
include "default_includes.php";
include "mysql.php";
include "gamestoolkit.php";
include "htmltoolkit.php";
include "steamtoolkit.php";

if (isset($_POST["customurl"]) && strlen(trim($_POST["customurl"])) > 0){
	$customurl = $_POST["customurl"];

	$steamid = getSteamLongID($customurl); // steamtoolkit.php, returns Steam64 ID from customURL ID

	if ($steamid == 1){ // error checking
		printErrorPage("An unknown error happened when handling JSON recieved from the Steam servers. SteamID: " . $steamid); // htmltoolkit.php
		die();
	} elseif ($steamid == 2){
		printErrorPage("The customURL ID you entered does not exist in Steam's database."); // htmltoolkit.php
		die();
	} elseif ($steamid == 3){
		printErrorPage("An unknown error happened."); // htmltoolkit.php
		die();
	}

	$ownedGames = getOwnedGames($steamid); // steamtoolkit.php, returns array of gameIDs

	if ($ownedGames == 1){ // error checking
		printErrorPage("An unknown error happened when handling JSON recieved from the Steam servers. ownedGames: " . $ownedGames . ", steamID: " . $steamid); // htmltoolkit.php
		die();
	} elseif ($ownedGames == 2) {
		printErrorPage("You have no games."); // htmltoolkit.php
		die();
	}

	$conn = new mysqli($sql_servname, $sql_username, $sql_password, $sql_database);

	if ($conn->connect_error){
		printErrorPage("Failed to connect to database: " . $conn->connect_error);
		die();
	}

	$sql = "SELECT games FROM users WHERE username = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $_SESSION["user_name"]); //to-do: check if session["username"] exists
	$result = $stmt->execute();

	if ($result === FALSE){
		printErrorPage("Something went wrong: " . $conn->error);
		die();
	}

	$stmt->bind_result($json);
	$stmt->store_result();

	if ($stmt->num_rows == 1){
		$stmt->fetch();
		$games = json_decode($json);
		foreach ($ownedGames as $game) { // game = an appid

			//$name = getGameName($game); // steamtoolkit.php, returns specified game's name // NOTE: Decided to do this in display();

			/*if ($name == 1){ // error checking
				printErrorPage("An unknown error happened when handling JSON recieved from the Steam servers."); // htmltoolkit.php
				die();
			} elseif ($name == 2) {
				printErrorPage("We couldn't access a game that you own."); // htmltoolkit.php
				die();
			}*/

			$count = getAchievedAchievementsCount($game, $steamid); //steamtoolkit.php, returns achieved achievement count of specified game, has a WAY too long name

			if ($count == -1){ // error checking
				printErrorPage("An unknown error happened when handling JSON recieved from the Steam servers. Appid: " . $game . ", SteamID: " . $steamid . ", Count: " . $count); // htmltoolkit.php
				die();
			} elseif ($count == -2){
				printErrorPage("We couldn't access a game that you own."); // htmltoolkit.php
				die();
			}

			$name = $game . "_steam";

			$count = $count . " achievements";

			$games = addGame($games, $name, $count);
		}

		$sql = "UPDATE users SET games = ? WHERE username = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ss", json_encode($games), $_SESSION["user_name"]);
		$result = $stmt->execute();

		if ($result !== TRUE){
			printErrorPage("Something went wrong: " . $conn->error);
			die();
		}

		echo "<center><h5 class=\"success\">Adding game successful.</h5></center>";
	} else {
		//absolute mindfuck (or maybe $_SESSION["username"] doesn't exist)
		die("<center><h1>Fatal error happened. " . $stmt->num_rows . " " . $_SESSION["user_name"] . "</h1></center>");
	}

	
} else {
	printErrorPage("You didn't enter all the required information."); // htmltoolkit.php
	die(); // horrible way to die
}
?>