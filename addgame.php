<?php
include "default_includes.php";
include "mysql.php";
include "htmltoolkit.php";
include "gamestoolkit.php";

session_start();

if (isset($_POST["name"]) && isset($_POST["level"])){
	$name = $_POST["name"];
	$level = $_POST["level"];

	if (strlen(trim($name)) == 0 && strlen(trim($level)) == 0){
		printErrorPage("You didn't enter all the required info").
		die();
	}

	$conn = new mysqli($sql_servname, $sql_username, $sql_password, $sql_database);

	if ($conn->connect_error){
		printErrorPage("Failed to connect to database: " . $conn->connect_error);
		die();
	}

	$sql = "SELECT games FROM users WHERE username = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $_SESSION["username"]); //to-do: check if session["username"] exists
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
		$games_new = addGame($games, $name, $level);

		$sql = "UPDATE users SET games = ? WHERE username = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ss", json_encode($games_new), $_SESSION["username"]);
		$result = $stmt->execute();

		if ($result !== TRUE){
			printErrorPage("Something went wrong: " . $conn->error);
			die();
		}

		printSuccessPage("Successfully added game!");
	} else {
		//absolute mindfuck (or maybe $_SESSION["username"] doesn't exist)
		die();
	}

} else {
	printErrorPage("You didn't enter all the required info");
	die();
}
?>