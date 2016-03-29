<?php
class Interpreter{
	private $db;
	
	public function __construct($relative_path) {
		$this->db = new SQLite3($relative_path);
	} 

	public function register_user($username, $email, $password) {
		return $this->query_send('INSERT INTO users 
								  (username, email, password)
								  VALUES (?, ?, ?)',
								  $username, $email, $password);
	}
	
	public function validate_user($username, $password) {
		$result = $this->query_get('SELECT password FROM users
									WHERE username = ?', $username);
		 return ($result['password'] == $password);
	}
	
	public function add_position($username, $position) {
		return $this->query_send('INSERT OR IGNORE INTO positions
								  (username, position) VALUES (?, ?)',
								  $username, $position); 
	}
	public function go_to_position($username, $position) {
		return $this->query_get('SELECT position, moves FROM positions
					  			 WHERE username = ? AND position = ?', 
					  			 $username, $position);
	}

	public function add_move($username, $position, $move) {
		$result = $this->query_get('SELECT moves FROM positions
				   					WHERE username = ? AND position = ?',
							 		$username, $position);
		if ($result) {
			$moves = $result['moves'];
			$moves = $moves . $move;
			return $this->query_send('REPLACE INTO positions (moves) VALUES (?)
					           		  WHERE username = ? AND position = ?',
					           		  $moves, $username, $position);
		}
		else return false;
	}
	
	/* Stupid delete */
	public function delete_move($username, $position, $move) {

		$result = $this->query_get('SELECT moves FROM positions
								    WHERE username = ? AND position = ?',
						     		$username, $position);
		if ($result) {
			$moves = $result['moves'];
			$moves = str_replace($move, "", $moves);
			return $this->query_send('REPLACE INTO positions (moves) VALUES (?) 
					      	          WHERE username = ? AND position = ?', 
							   		  $username, $position);
		}
		else return false;
		
	}

	private function query_get($query, ...$arguments) {
		$stmt = $this->db->prepare($query);
		for ($i = 1; $i < func_num_args(); $i++) {
			$stmt->bindValue($i, SQLite3::escapeString(func_get_arg($i)));
		}
		if ($result = $stmt->execute()) {
			return $result->fetchArray(SQLITE3_ASSOC);
		}
		else return false;
	}

	private function query_send($query, ...$arguments) {
		$stmt = $this->db->prepare($query);
		for ($i = 1; $i <= func_num_args(); $i++) {
			$stmt->bindValue($i, SQLite3::escapeString(func_get_arg($i)));
		}
		return ($stmt->execute() != false);
	}
}
?>
