<?php
include "default_includes.php";
include "mysql.php";

if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"])){
	$username = $_POST["username"];
	$password = $_POST["password"];
	$email = $_POST["email"];
	$picture = NULL;
	$games = "{}";
	$displayname = "--";
	$banned = FALSE;

	$password = password_hash($password, PASSWORD_DEFAULT);

	if(isset($_POST["displayname"])) { $displayname = $_POST["displayname"]; }
	if(isset($_FILES["image"])) { $picture = addslashes(file_get_contents($_FILES['image']['tmp_name'])); }

	if (strlen(trim($email)) == 0 || strlen(trim($username)) == 0 || strlen(trim($password)) == 0){
		printErrorPage("You didn't enter all required info.");
		die();
	}

	$conn = new mysqli($sql_servname, $sql_username, $sql_password, $sql_database);

	if ($conn->connect_error){
		printErrorPage("Failed to connect to database: " . $conn->connect_error);
		die();
	}

	$sql = "SELECT username FROM users WHERE username=?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $username);
	$result = $stmt->execute();

	if ($result->num_rows == 0){
		$sql = "INSERT INTO users (username, password, picture, email, games, banned, displayname) VALUES (?, ?, ?, ?, ?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ssbssis", $username, $password, $picture, $email, $games, $banned, $displayname);
		$result = $stmt->execute();

		if ($result === TRUE){
			printSuccessPage();
		} else {
			printErrorPage("Something went wrong: " . $conn->error);
			die();
		}
	} else {
		printErrorPage("Username is already taken.");
	}

	
} else {
	printErrorPage("You didn't enter all required info.");
	die();
}

function printErrorPage($error){
	echo printHtmlPage(printCentered("<h5 class=\"error\">" . $error . "</h5>"));
}

function printSuccessPage(){
	echo printHtmlPage(printCentered("<h5 class=\"success\">Registration successful.</h5>" . printUrl("login.php", "Click here to log in.")));
}

function printHtmlPage($text){
	return "<html><body>" . $text . "</body></html>";
}

function printUrl($url, $text){
	return "<p><a href=\"" . $url . "\">" . $text . "</a></p>";
}

function printCentered($inner){
	return "<center>" . $inner . "</center>";
}
?>