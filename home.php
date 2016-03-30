<?php
session_start();
if (!isset($_SESSION['username'])) {
	header("Location: index.php");
}
?>
<!doctype html>
<html>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="scripts/home.js"></script>
	<script src="scripts/serialize.js"></script>
	<link rel="stylesheet" type="text/css" href="stylesheets/home.css">
	<meta charset="utf-8" />
	<title>My Repertoire</title>
</head>
<body>
	<h1>Welcome, <?php echo $_SESSION['username']?>!</h1>
	<nav>
		<a href="home.php">Home</a>
		<a href="logout.php">Log Out</a>
	</nav>
	<canvas id="board" class="main" height="500" width="500"> 
		<p>Alt text</p>			
	</canvas>
	<table id="candidates" class="main">
		<caption id="candidates_caption">No moves to display</caption>
		<tr id="header">
			<th>Rank</th>
			<th>Move</th>
			<th>Eval</th>
			<th>Remove</th>
		</tr>
	</table>
</body>
</html>
