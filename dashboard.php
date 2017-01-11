<?php
include "default_includes.php";

session_start();
$username = "";
$userid = 0;

if (isset($_SESSION["user_name"]) && isset($_SESSION["user_id"])){
	$username = $_SESSION["user_name"];
	$userid = $_SESSION["user_id"];
} else {
	header("Location: http://moger.net/gameboard/login.php");
	die();
	// if anyone knows how to redirect to login.php without specifying the whole url, please do a pull request!
}

?>
<html>
	<body>
		<center>
			<h3>Welcome, <?php echo $username; ?>!</h3>
			<h5>This page is under construction... <a href="logout.php">Click here to log out.</a></h5>
		</center>
	</body>
</html>