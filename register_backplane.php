<?php
include "default_includes.php";
include "mysql.php";
include "htmltoolkit.php";

$uploadImage = false;

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
	if(isset($_FILES['image'])) {
		$target_file = "img/" . basename($_FILES["image"]["name"]);
		$imageFileType = pathinfo($username,PATHINFO_EXTENSION);
		$check = getimagesize($_FILES["image"]["tmp_name"]);
		if($check === false){
			printErrorPage("Sorry, this is not an image!");
			die();
		}

		if ($imageFileType != "png"){
			printErrorPage("Sorry, the site only allows .png images!");
			die();
		}

		if($_FILES["image"]["size"] > 5000000){
			printErrorPage("Sorry, this image is too large! (" . $_FILES["image"]["size"] . " bytes > 5 megabytes)");
			die();
		}

		$uploadImage = true;
	}

	if (strlen(trim($email)) == 0 || strlen(trim($username)) == 0 || strlen(trim($password)) == 0){
		printErrorPage("You didn't enter all required info.");
		die();
	}

	$conn = new mysqli($sql_servname, $sql_username, $sql_password, $sql_database);

	if ($conn->connect_error){
		printErrorPage("Failed to connect to database: " . $conn->connect_error);
		die();
	}

	$sql = "SELECT username FROM users WHERE username = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $username);
	$result = $stmt->execute();

	if ($result === FALSE){
		printErrorPage("Something went wrong: " . $conn->error);
		die();
	}

	$stmt->store_result();

	if ($stmt->num_rows == 0){
		$sql = "INSERT INTO users (username, password, email, games, banned, displayname) VALUES (?, ?, ?, ?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ssssis", $username, $password, $email, $games, $banned, $displayname);
		$result = $stmt->execute();

		if ($result === TRUE){
			if ($uploadImage === true){
				if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
					printErrorPage("For an unknown reason, your profile image could not be uploaded.");
				}
			}
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
?>