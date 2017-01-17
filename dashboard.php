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
			<br>
			<h2>Add a game manually</h2>
			<form action="addgame.php" method="POST">
				<h4>Game Name:</h4> <!-- praise the rhyme lord !-->
				<input name="name" type="text">
				<h4>Game Level:</h4>
				<input name="level" type="text">
				<br>
				<input name="submit" type="submit" value="Add">
			</form>
			<br>
			<h2>Change the levels of your manually added games</h2>
			<form action="changegame.php" method="POST">
				<h4>Game Name:</h4> <!-- praise the rhyme lord !-->
				<input name="name" type="text">
				<h4>New Level:</h4>
				<input name="level" type="text">
				<br>
				<input name="submit" type="submit" value="Add">
			</form>
			<br>
			<h2>Attach your game accounts</h2>
			<br>
			<h3>Steam</h3>
			<form action="addsteam.php" method="POST">
				<h4>customURL ID (<a href="what_is_customurl_id.php">WTF is this?</a>):</h4>
				<input name="customurl" type="text">
				<input name="submit" type="submit" value="Attach">
			</form>
			<br>
			<h3>More coming soon...</h3>
		</center>
	</body>
</html>
