<?php

function send($data) {
	if ($data) {
		echo JSON_encode($data);
	}
	else {
		header('HTTP/1.1 400 Bad Request');
	}
}

function choose_method($db, $action) {
	$user = $_SESSION['username'];
	switch($action) {
		case "add_position":
			return $db->add_position($user, $_POST['position']);
		case "go_to_position": 
			return $db->go_to_position($user, $_POST['position']);
		case "add_move":
			if ($db->add_move($user, $_POST['position'], $_POST['move'])) {
				return $db->go_to_position($user, $_POST['position']);
			}
			else return false;
		case "delete_move":
			if ($db->delete_move($user, $_POST['position'],	$_POST['move'])) {
				return $db->go_to_position($user, $_POST['position']);
			}
			else return false;
		default: return false;
	}
}

session_start();
if (isset($_SESSION['username'])) {
	require('./database/Interpreter.php');
	$db = new Interpreter('./database/repertoire.db');
	send(choose_method($db, $_POST['action']));
}
else {
	header("Location: index.php");
}
?>

