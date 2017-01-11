<?php
include "default_includes.php";
?>
<html>
	<body>
		<form action="login_backplane.php" method="post">
				<p>Username:</p>
				<input type="text" name="username" required>
				<p>Password:</p>
				<input type="password" name="password" required>
				<br>
				<br>
				<input type="submit" name="submit" value="Login">
			</form>
	</body>
</html>