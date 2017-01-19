<?php
include_once "default_includes.php";
include_once "config.php";
include_once "htmltoolkit.php";
include_once "gamestoolkit.php";
include_once "simpletoolkit.php";
include_once "steamtoolkit.php";

if (isset($_GET["id"])){
	$id = intval($_GET["id"]);

	if (strlen(trim($id)) == 0){
		header("Location: http://moger.net/gameboard/");
	}

	$conn = new mysqli($sql_servname, $sql_username, $sql_password, $sql_database);

	if ($conn->connect_error){
		printErrorPage("Failed to connect to database: " . $conn->connect_error);
		die();
	}

	$sql = "SELECT games, username, displayname FROM users WHERE id = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);
	$result = $stmt->execute();

	if ($result === FALSE){
		printErrorPage("Something went wrong: " . $conn->error);
		die();
	}

	$stmt->bind_result($json, $username, $displayname);
	$stmt->store_result();

	if ($stmt->num_rows == 1){
		$stmt->fetch();
		$games = json_decode($json);
		$games_new = apiUpdate($games);
		if ($games_new != $games){
			$json_new = json_encode($games_new);

			$sql = "UPDATE `users` SET `games` = ? WHERE `id` = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("si", json_encode($games_new), $id);
			$result = $stmt->execute();

			if ($result !== TRUE){
				printErrorPage("Something went wrong: " . $conn->error);
				die();
			}
		}
		display($username, $displayname, $games_new);
	} else {
		if ($stmt->num_rows == 0){
			header("Location: http://moger.net/gameboard/");
		} else {
			//absolute mindfuck
		}
		die();
	}

} else {
	header("Location: http://moger.net/gameboard/");	
}

function display($username, $displayname, $games){
	//HTML MAGIC!
	$games_array = (array)$games
	?>
	<html>
		<body>
			<center>
				<h2><?php echo $username; ?></h2>
				<br>
				<table style="border-collapse: collapse;">
					<?php
						$keys = array_keys($games_array);
						unset($keys["timestamp"]); // y u no work
						// challange accepted
						foreach($keys as $key){
							global $steam;
							$game = $key;
							$gamename = $key;
							if (preg_match('/\d+_steam/', $game)){
								if ($steam === NULL){
									printErrorPage("Steam is NULL!");
									die();
								}
								$gamename = getGameName(substr($game, 0, strlen($game) - strlen("_steam")), $steam);
								if ($gamename == 1) { //error checking
									printErrorPage("An unknown error happened when handling JSON recieved from the Steam servers."); // htmltoolkit.php
									die();
								}
							}
							if ($game != "timestamp" && $games_array[$key] != 0 && $key != "id_steam"){
								echo "<tr style=\"border: none;\"><td style=\"border-right: solid 1px #ffffff; color: #ffffff;\">";
								if (preg_match('/\d+_steam/', $game)){
									echo "<a href=\"http://store.steampowered.com/app/";
									echo substr($game, 0, strlen($game) - strlen("_steam"));
									echo "/\">";
								}
								echo $gamename;
								if (preg_match('/\d+_steam/', $game)){
									echo "</a>";
								}
								echo "</td><td style=\"border-left: solid 1px #ffffff; color: #ffffff;\">" . $games_array[$key];
								if (preg_match('/\d+_steam/', $game)){
                                                                        echo " achievements";
                                                                }
								echo "</td></tr>";
							} // success kid
						}
					?>
				</table>
			</center>
		</body>
	</html>
<?php
}
?>
