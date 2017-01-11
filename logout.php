<?php
if (isset($_SESSION["user_name"]) || isset($_SESSION["user_id"])){
	session_unset();
}
header("Location: http://moger.net/gameboard/index.php");
die();
// if anyone knows how to redirect to login.php without specifying the whole url, please do a pull request!
?>