<?php
include "default_includes.php";
//include "mysql.php";

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
		var_dump($json);
		$games = json_decode($json);
		var_dump($games);
		$games_new = apiUpdate($games);
		if ($games_new != $games){
			$json_new = json_encode($games_new);

			$sql = "UPDATE `users` SET `games` = ? WHERE `id` = ?";
			$stmt = $conn->prepare($sql);
			die($conn->error);
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

function apiUpdate($games){
	//this doesn't actually update api stuff yet, just the timestamp
	var_dump($games);
	$return = (array)$games;
	var_dump($return);
	$keys = array_keys($return);
	var_dump($keys);
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
	var_dump($return);
	return (object)$return;
}

function display($username, $displayname, $games){
	//HTML MAGIC!
	$games_array = (array)$games
	?>
	<html>
		<body>
			<center>
				<?php
					if(file_exists("img/" . $username . ".png")){
				?>
				<img src="img/<?php echo $username; ?>.png">
				<?php
					}
				?>
				<h2><?php echo $username; ?></h2>
				<br>
				<table style="border-collapse: collapse;">
					<?php
						$keys = array_keys($games_array);
						unset($keys["timestamp"]);
						foreach($keys as $key){
							echo "<tr style=\"border: none;\"><td style=\"border-right: solid 1px #ffffff;\">" . $key . "</td><td style=\"border-left: solid 1px #ffffff;\">" . $games_array[$key] . "</td></tr>";
						}
					?>
				</table>
			</center>
		</body>
	</html>
<?php
}
?>