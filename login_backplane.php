<?php
include "default_includes.php";
include "mysql.php";
include "htmltoolkit.php";

if (isset($_POST["username"]) && isset($_POST["password"])){
	$username = $_POST["username"];
	$password = $_POST["password"];

	if (strlen(trim($username)) == 0 || strlen(trim($password)) == 0){
		printErrorPage("You didn't enter all required info.");
		die();
	}

	$conn = new mysqli($sql_servname, $sql_username, $sql_password, $sql_database);

	if ($conn->connect_error){
		printErrorPage("Failed to connect to database: " . $conn->connect_error);
		die();
	}

	$sql = "SELECT password, id FROM users WHERE username = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $username);
	$result = $stmt->execute();

	if ($result === FALSE){
		printErrorPage("Something went wrong: " . $conn->error);
		die();
	}

	$stmt->bind_result($selectpassword, $selectid);
	$stmt->store_result();

	if ($stmt->num_rows == 1){
		$stmt->fetch();
		if (password_verify($password, $selectpassword)){
			session_start();
			$_SESSION["user_name"] = $username;
			$_SESSION["user_id"] = $selectid;
			dashboardRedirect();
			die();
		} else {
			printWrongPassPage();
		}
	} else {
		if ($stmt->num_rows == 0){
			printErrorPage("User not found.");
		} else {
			printErrorPage("An unexcepted error occured. Code: multiple_users");
		}
		die();
	}
} else {
	printErrorPage("You didn't enter all required info.");
	die();
}
?>