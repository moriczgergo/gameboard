<?php
// default_includes.php - Made by skiilaa/moriczgergo
// This .PHP file is a part of the GameBoard project.
?>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="cache-control" content="max-age=0" />
		<meta http-equiv="cache-control" content="no-cache" />
		<meta http-equiv="expires" content="0" />
		<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
		<meta http-equiv="pragma" content="no-cache" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
		<link rel="stylesheet" href="site.css">
		<title>GameBoard</title>
	</head>
	<body>
		<center>
			<div class="header">
				<div>invisible</div>
				<a href="index.php" class="logolink"><img src="logo.png" alt="GameBoard"></img></a>
				<form action="search.php" method="post" id="searchform">
					<input name="query" placeholder="Enter username here..." class="noinputbreak" />
					<input type="submit" name="submit" value="Search" class="noinputbreak" />
				</form>
				<br>
			</div>
			<p>&copy; 2016 Móricz Gergő - All Rights Reserved - <a href="https://github.com/moriczgergo/gameboard/">GitHub</a> - <a href="https://twitter.com/skiilaa">Twitter</a></p>
			<hr>
		</center>
		<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
	</body>
</html>
