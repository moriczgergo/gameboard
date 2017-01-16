<?php
include "default_includes.php";
?>
<html>
	<body>
			<form action="register_backplane.php" method="post">
				<p>Username*:</p>
				<input type="text" name="username" required>
				<p>Password*:</p>
				<input type="password" name="password" required>
				<p>E-Mail*:</p>
				<input type="text" name="email" required>
				<p>Display name:</p>
				<input type="text" name="displayname">
				<p>Profile picture:</p>
				<input name="image" type="file">
				<br>
				<br>
				<input type="submit" name="submit" value="Register">
				<p>* = required</p>
			</form>
	</body>
</html>