function serialize(data) {
	var result = {};
	for (key in data) {
		result[key] = serialize_item(key, data[key]);
	}
	console.log(result);
	return result;
}

function serialize_item(key, value) {
	var result = "";
	switch (key) {
		case "position":
			result += value.color + ",";
			result += value.to_move + ",";
			for (var i = 0; i < value.board.length; i++) {
				result += value.board[i].join("") + ";";
			}
			result += value.castle.join("") + ",";
			result += (value.en_passant == null) ? 
					  "-" : value.en_passant.join("");
			
			break;
		case "move":
			result += value.join("") + ",";
			break;
	}
	return result;
}

function deserialize(data) {
	var result = {};
	for (key in data) {
		result[key] = deserialize_item(key, data[key]);
	}
	return result;
}

function deserialize_item(key, value) {
	var result;
	switch (key) {
		case "position":
			value = value.split(",");
			result.color = value[0];
			result.board = value[1].split(";").map(function(row) {
				return row.split("");
			});
			result.castle = value[2].split("");
			result.en_passant = value[3] == "-" ? null : value[3].split("");
			break;
		case "move":
			result = value.split(""); 
			break;
	}
	return result;
}		
