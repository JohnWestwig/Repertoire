<?php
function format($data) {
	return JSON_encode($data);
}

function handle_event() {
	$username = $_SESSION['username'];                                           
    $db = new Interpreter;                                                       
    switch($_POST['event']) {                                                    
        case 'add':                                                              
            return $db->add_move($username, $_POST['position'], $_POST['move']);
		case 'delete':
			return $db->delete_move($username, $_POST['position'], 
									$_POST['move']);
		case 'goto':
			return $db->go_to_position($username, $_POST['position']);
		default:
			return false;           
    }   
}

session_start();
if (isset($_SESSION['username'])) {
	if ($result = handle_even($_POST['event'])) {
		echo format($result);
	}
	else echo "Unsupported action";
}	
?>
