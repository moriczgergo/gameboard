<?php
include "default_includes.php";

session_start();

if (isset($_SESSION["username"]) && isset($_SESSION["user_id"])){
	$username = $_SESSION["username"];
	$userid = $_SESSION["user_id"];
} else {
	header("Location: http://moger.net/gameboard/login.php");
	// if anyone knows how to redirect to login.php without specifying the whole url, please do a pull request!
}

?>
<html>
	<body>
		<center>
			<h3>Welcome, <?php echo $username; ?>!</h3>
			<h5>This page is under construction...</h5>
		</center>
	</body>
</html>