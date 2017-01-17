<?php
include "default_includes.php";
include "config.php";
include "htmltoolkit.php";

$query = "";

if (isset($_POST["query"])){
	$query = $_POST["query"];
}

$conn = new mysqli($sql_servname, $sql_username, $sql_password, $sql_database);

if ($conn->connect_error){
	printErrorPage("Failed to connect to database: " . $conn->connect_error);
	die();
}

$sql = "SELECT username, id FROM users LIKE '%?%'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$result = $stmt->execute();

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
		printErrorPage("The search returned no results.");
		die();
	} else {
		while($row = $result->fetch_assoc()) {
        	echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
    	}
	}
}

function printErrorPage($error){
	echo printHtmlPage(printCentered("<h5 class=\"error\">" . $error . "</h5>");
}
?>