<?php
$db = new SQLite3('repertoire.db');
$result = $db->query('SELECT * FROM users');
while($row = $result->fetchArray(SQLITE3_ASSOC)) {
	var_dump($row);
}

$result = $db->query('SELECT * FROM positions');
while($row = $result->fetchArray(SQLITE3_ASSOC)) {
    var_dump($row);
}

/*$result = $db->query('SELECT * FROM sqlite_master');
while($row = $result->fetchArray(SQLITE3_ASSOC)) {                               
    var_dump($row);                                                              
}   
*/
?>
