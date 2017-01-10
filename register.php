<?php
include "default_includes.php";

$error = "";

if (empty($_POST)){ //if no reg data sent
	printRegPage();
} else { //if reg data sent
	if (isset($_POST["username"] && isset($_POST["password"]) && isset($_POST["email"])){
		$username = $_POST["username"];
		$password = $_POST["password"];
		$email = $_POST["email"];
		$picture = NULL;

		if(isset($_POST["displayname"])) { $displayname = $_POST["displayname"]; }
		if(isset($_FILES["image"])) { $picture = addslashes(file_get_contents($_FILES['image']['tmp_name'])); }

		if (strlen(trim($email)) == 0 || strlen(trim($username)) == 0 || strlen(trim($password)) == 0){
			$error = "You didn't enter all required info.";
			printRegPage();
			die();
		}

		$conn = new mysqli($sql_servname, $sql_username, $sql_password, $sql_database);

		if ($conn->connect_error){
			$error = "Failed to connect to database: " . $conn->connect_error;
			printRegPage();
			die();
		}

		$sql = "INSERT INTO users (username, password, picture, email) VALUES (?, ?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ssbs", $username, $password, $picture, $email);
		$result = $stmt->execute();

		if ($result === TRUE){
			printSuccessPage();
		} else {
			$error = "Something went wrong: " . $conn->error;
			printRegPage();
			die();
		}

	} else {
		$error = "You didn't enter all required info.";
		printRegPage();
		die();
	}
}

function printRegPage(){
	echo "<html><body>";
	if ($error != ""){
		echo "<center><h5 class=\"error\">";
		echo $error;
		echo "</h5></center>";
	}
	echo "<form action=\"register.php\" method=\"post\"><p>Username*:</p><input type=\"text\" name=\"username\" required><p>Password*:</p><input type=\"password\" name=\"password\" required><p>E-Mail*:</p><input type=\"text\" name=\"email\" required><p>Display name:</p><input type=\"text\" name=\"displayname\"><p>Profile picture:</p><input name=\"image\" id=\"image\" accept=\"image/JPEG\" type=\"file\"><p>* = required</p></body></html>";
}

function printSuccessPage(){
	echo "<html><body><h1>Registration successful.</h1></body></html>";
}