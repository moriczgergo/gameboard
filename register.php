<?php
include "default_includes.php";

$sql_fault = '(empty)';

if (!isset($_POST["submit"])){ //if no reg data sent
	printRegPage();
}
else
{ //if reg data sent
	if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["email"])){
		$username = $_POST["username"];
		$password = $_POST["password"];
		$email = $_POST["email"];
		$picture = NULL;
		$games = "{}";
		$displayname = "--";

		$password = password_hash($password, PASSWORD_DEFAULT);

		if(isset($_POST["displayname"])) { $displayname = $_POST["displayname"]; }
		if(isset($_FILES["image"])) { $picture = addslashes(file_get_contents($_FILES['image']['tmp_name'])); }

		if (strlen(trim($email)) == 0 || strlen(trim($username)) == 0 || strlen(trim($password)) == 0){
			$sql_fault = "You didn't enter all required info.";
			printRegPage();
			die();
		}

		$conn = new mysqli($sql_servname, $sql_username, $sql_password, $sql_database);

		if ($conn->connect_error){
			$sql_fault = "Failed to connect to database: " . $conn->connect_error;
			printRegPage();
			die();
		}

		$sql = "INSERT INTO users (username, password, picture, email, games, banned, displayname) VALUES (?, ?, ?, ?, ?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ssbssis", $username, $password, $picture, $email, $games, 0, $displayname);
		$result = $stmt->execute();

		if ($result === TRUE){
			printSuccessPage();
		} else {
			$sql_fault = "Something went wrong: " . $conn->error;
			printRegPage();
			die();
		}

	} else {
		$sql_fault = "You didn't enter all required info.";
		printRegPage();
		die();
	}
}

function printRegPage(){
	echo "<html><body>";
	if ($GLOBALS["sql_fault"] != "(empty)"){
		echo "<center><h5 class=\"error\">";
		echo $GLOBALS["sql_fault"];
		echo "</h5></center>";
	}
	echo "<form action=\"register.php\" method=\"post\"><p>Username*:</p><input type=\"text\" name=\"username\" required><p>Password*:</p><input type=\"password\" name=\"password\" required><p>E-Mail*:</p><input type=\"text\" name=\"email\" required><p>Display name:</p><input type=\"text\" name=\"displayname\"><p>Profile picture:</p><input name=\"image\" id=\"image\" accept=\"image/JPEG\" type=\"file\"></br></br><input type=\"submit\" value=\"Register\"></form><p>* = required</p></body></html>";
}

function printSuccessPage(){
	echo "<html><body><h1>Registration successful.</h1></body></html>";
}
