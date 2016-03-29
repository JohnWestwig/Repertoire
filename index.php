<?php
require('./database/Interpreter.php');
session_start();
if (isset($_SESSION['username']) != "") {
	header("Location: home.php");
}
else {
	if(isset($_POST['login'])) {
		$db = new Interpreter('./database/repertoire.db');
		if ($db->validate_user($_POST['username'], md5($_POST['password']))) {
			$_SESSION['username'] = $_POST['username'];
			header("Location: home.php");
		}
		else {
			echo "Username or password incorrect";
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>
	<form method="post">
		<table>
            <tr>
                <td><h4>Username:</h4></td>
                <td><input type="text" name="username" required/></td>
            </tr>
            <tr>
                <td><h4>Password:</h4></td>
                <td><input type="password" name="password" required/></td>
            </tr>
            <tr>
                <td><button type="submit" name="login">Log In</button></td>
            </tr>
            <tr>
                <td><a href="register.php">Not yet a member? Register here</a></td>
            </tr>	
		</table>
	</form>
</body>
</html>


