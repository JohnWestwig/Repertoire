<?php
/* This script creates a database with the following tables:
 * Users:
 *     [Username][E-Mail][Password]
 *
 * Positions:
 * 	   [Username][Position][Candidate Moves][User's Color]
 */
$file_path = 'repertoire.db';
$db;
try {
	$db = new SQLite3($file_path);
}
catch (Exception $e) {
	echo "Error connecting to database";
	echo $e->getMessage();
}
chmod($file_path, 0706);
$db->exec('CREATE TABLE IF NOT EXISTS users (username TEXT PRIMARY KEY, 
											 email TEXT UNIQUE,
											 password TEXT)');
$db->exec('CREATE TABLE IF NOT EXISTS positions (username TEXT,
												 position TEXT UNIQUE,
												 moves TEXT)');
?>
