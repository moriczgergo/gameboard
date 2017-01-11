<?php
include "default_includes.php";
include "mysql.php";

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

	$sql = "SELECT password, id FROM users WHERE username=\"" . $username . "\"";
	$result = $conn->query($sql);

	if ($result->num_rows == 1){
		$row = $result->fetch_assoc();
		if (password_verify($password, $row["password"])){
			session_start();
			$_SESSION["user_name"] = $username;
			$_SESSION["user_id"] = $row["id"];
			dashboardRedirect();
			die();
		} else {
			printWrongPassPage();
		}
	} else {
		if ($result->num_rows == 0){
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
function printErrorPage($error){
	echo printHtmlPage(printCentered("<h5 class=\"error\">" . $error . "</h5>" . printUrl("login.php", "Try again.")));
}

function printWrongPassPage($error){
	echo printHtmlPage(printCentered(PrintErrorPage("Wrong password.") . printUrl("login.php", "Try again.")));
}

function dashboardRedirect(){
	header("Location: http://moger.net/gameboard/dashboard.php");
	// if anyone knows how to redirect to dashboard.php without specifying the whole url, please do a pull request!
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