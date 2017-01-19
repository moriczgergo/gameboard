<?php
include_once "default_includes.php";
session_start();
?>
<html>
	<body>
		<center>
			<h2>The perfect page to show off your performance in games.</h2>
			<h5><?php if (isset($_SESSION["user_name"])) { echo "<a href=\"dashboard.php\">Go to your dashboard</a>"; } else { echo "<a href=\"register.php\">Register now</a> or <a href=\"login.php\">log in</a>"; } ?></h5>
		</center>
	</body>
</html>
