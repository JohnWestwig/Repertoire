/* Declare "enums" */
var Colors = {
	"WHITE" : 0,
	"BLACK" : 1
};

var Actions = {
	"ADD_MOVE": "add_move",
	"DELETE_MOVE": "delete_move",
	"GO_TO_POSITION": "go_to_position"
};

function load_image(src) {
	var img = new Image();
	img.src = src;
	return img;
}

var Images = {
	"p": load_image("Images/b_pawn.png"),
    "n": load_image("Images/b_knight.png"),
    "b": load_image("Images/b_bishop.png"),
   	"r": load_image("Images/b_rook.png"),
	"q": load_image("Images/b_queen.png"),
   	"k": load_image("Images/b_king.png"),
   	"P": load_image("Images/w_pawn.png"),
   	"N": load_image("Images/w_knight.png"),
   	"B": load_image("Images/w_bishop.png"),
   	"R": load_image("Images/w_rook.png"),
   	"Q": load_image("Images/w_queen.png"),
	"K": load_image("Images/w_king.png")
};

/*Initialize constants*/
var DARK_COLOR = "#008080";
var LIGHT_COLOR = "#FFFFFF";
var HIGHLIGHT_COLOR = "#FF00FF";
var SQ_SIZE;

/*Graphical state*/
var current_view_color = Colors.WHITE;
var current_square = {row: -1, col: -1};

/*Board state*/
var board_state = {
	position: {board: [['r', 'n', 'b', 'q', 'k', 'b', 'n', 'r'],
			   		   ['p', 'p', 'p', 'p', 'p', 'p', 'p', 'p'],
			   		   ['_', '_', '_', '_', '_', '_', '_', '_'],
			   		   ['_', '_', '_', '_', '_', '_', '_', '_'], 
			   		   ['_', '_', '_', '_', '_', '_', '_', '_'],
			   		   ['_', '_', '_', '_', '_', '_', '_', '_'],
               		   ['P', 'P', 'P', 'P', 'P', 'P', 'P', 'P'],
			   		   ['R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R']],
			   to_move: 0,
			   castle: [1, 1, 1, 1],
			   en_passant: null,
			   color: Colors.WHITE},
	moves: null, 
	path: null 
};

/*Startup*/
$(window).load(function() {
	SQ_SIZE = $("#board").height() / 8;
	data = {position: board_state.position};
	process_action("GO_TO_POSITION", data);
});

////////////////////////////////////////////////////////////////////////////////
//////////////////----Handle server request and response----////////////////////
////////////////////////////////////////////////////////////////////////////////
function process_action(action, data) {
	if (action in Actions) {
		data = serialize(data);
		data.action = Actions[action];
		$.ajax({
			type: "POST",
			url: "process_action.php",
			data: data,
			success: update_client
		});
 	}
	else {
		console.log("Action: " + action + " not supported");
	}
}

function update_client(data) {
	data = deserialize(data);
	console.log("Deserialized data:");
	console.log(data);
	for (key in data) {
		if (key in board_state) {
			board_state[key] = data[key];
		}
	}
	draw();
}

////////////////////////////////////////////////////////////////////////////////
////////////////////----Bind events to their handlers----///////////////////////
////////////////////////////////////////////////////////////////////////////////
$(document).ready(function() {
	/* Follow move via board click*/
	$("#board").click(function(e) {
		rect = this.getBoundingClientRect();		
		new_square = {row: Math.floor((e.clientY - rect.top) / SQ_SIZE), 
					  col: Math.floor((e.clientX - rect.left) / SQ_SIZE)};
		if (current_square.row == -1 || current_square.col == -1) {
			current_square = new_square;
		}
		else if (new_square.row == current_square.row &&
			new_square.col == current_square.col) {	
			current_square = {row: -1, col: -1};
		} 	
		else {
			var move = [current_square.row, current_square.col,
						new_square.row, new_square.col];
			
			data = {position: generate_position(move)};
			process_action("GO_TO_POSITION", data);
			console.log(data.position);
			current_square = new_square;
		}
		draw();
	});
});
////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////----Moves----//////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
function is_legal(move) {
	return true;
}

function generate_position(move) {
	if (is_legal(move)) {
		var new_position = JSON.parse(JSON.stringify(board_state.position));
		if (board_state.position.board[move[0]][move[1]].toUpperCase == 'P' &&
			abs(move[2] - move[0]) == 2) {
			new_position.en_passant = "" + (move[0] + 1) + move[1];	
		}
		else {
			new_position.en_passant = null;
		}
		
		if (board_state.position.board[move[0]][move[1]].toUpperCase == 'K') {
			new_position.castle = [0, 0, 0, 0];
		}
		alert(move);
		new_position.board[move[2]][move[3]] = 
			board_state.position.board[move[0]][move[1]];
		new_position.board[move[0]][move[1]] = '_';
		new_position.color = board_state.position.color == Colors.WHITE ?
							 Colors.BLACK : Colors.WHITE;
		return new_position; 
	}
	else return false;
}

////////////////////////////////////////////////////////////////////////////////
///////////////////////////////----Graphics----/////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function draw() {
	draw_board();
	draw_moves();
	draw_path();
}


function draw_board() {
	cvs = document.getElementById("board")
	ctx = cvs.getContext("2d");
	for (var row = 0; row < 8; row++) {
		for (var col = 0; col < 8; col++) {
			ctx.fillStyle = ((row + col) % 2 == 0) ? LIGHT_COLOR : DARK_COLOR;
			if (current_square.row == row && current_square.col == col) {	
				ctx.fillStyle = HIGHLIGHT_COLOR;
			}	
			ctx.fillRect(col * SQ_SIZE, row * SQ_SIZE, SQ_SIZE, SQ_SIZE); 	
		    let sq = board_state.position.board[row][col];	
			if (sq != '_') {
				ctx.drawImage(Images[sq], col * SQ_SIZE, row * SQ_SIZE,
							  SQ_SIZE, SQ_SIZE);
			}
		}
	}	
}

function draw_moves() {
	if (board_state.moves == null) {
		$("#moves_caption").text("No moves to display");
	}
	else {
		$("#moves_caption").text("");
		for (var i = 0; i < board_state.moves.length; i++) {
			$('#moves tr:last').after('<tr><td>' + (i+1) + '</td><td>' + 
									  board_state.moves[i] + '</td>' +
									  '<td>0.00</td><td>' + 
									  '<button>-</button></td></tr>'); 
		}		
	}
}

function draw_path() {
	
}

function change_view(color) {
	if (color in Colors) {
		current_view_color = color;
	}
	else {
		console.log("View color: " + color + " not supported");
	}
}


