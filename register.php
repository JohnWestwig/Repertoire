<?php
session_start();
require('./database/Interpreter.php');

if (isset($_SESSION['username'])) {
	header("Location: home.php");
}
else {
	if (isset($_POST["register"])) {
		$db = new Interpreter('./database/repertoire.db');
		if ($db->register_user($_POST['username'], $_POST['email'], 
				  		   md5($_POST['password']))) {
			$db->add_position($_POST['username'], 
							  "rnbqkbnr,pppppppp,________,________," . 
							  "________,________,PPPPPPPP,RNBQKBNR");
			header("Location: index.php");
		}
		else {
			echo "Username or E-Mail already exists!";
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta content="charset=utf-8"/>
	<link rel="stylesheet" type="text/css" href="stylesheets/register.css">
	<title>Register</title>
</head>

<body>
	<form method="post">
		<table>		
			<tr>
				<td><h4>Username:</h4></td>
				<td><input type="text" name="username" required/></td>
			</tr>
            <tr>
				<td><h4>E-Mail:</h4></td>
                <td><input type="email" name="email" required/></td>
            </tr>
            <tr>
				<td><h4>Password:</h4></td>
                <td><input type="password" name="password" required/></td>
            </tr>
            <tr>
                <td><button type="submit" name="register">Register</button></td>
            </tr>
			<tr>	
				<td><a href="index.php">Already registered? Log in here</a></td>
			</tr>
		</table>
	</form>
</body>
</html>


