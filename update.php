<?php
//Called from ajax when article is updated
// DOES NOT CHECK FOR CORRECT INPUT (still using prepared statements against injection). CHECKING DATATYPE SHOULD BE DONE BEFORE
//requires POST[id], POST[col], POST[value] for article id and column name and value

$id = $_GET['id'];
$col = $_GET['col'];
$value = $_GET['val'];

require "db_guest.php";
http_response_code(202);

//check if $col is valid
if($col === 'start' || $col === 'finish' || $col === 'count'){

	// Prepared state necessary bc $value is user input
	// Prepare statement

	if (!($stmt = $db->prepare("UPDATE current SET ".$col." = ? WHERE id = ?"))) {
		//echo "Prepare failed: (" . $db->errno . ") " . $db->error;
		http_response_code(400);
	}

	// Bind
	if(!$stmt->bind_param("ss", $value, $id)) {
		//echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		http_response_code(400);
	}

	// Execute
	if (!$stmt->execute()) {
		//echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		http_response_code(400);
	}

	$stmt->close();
	
	$stmt = $db->prepare("SELECT price, start, finish, count FROM current WHERE id = ?;");
	$stmt->bind_param("s", $id);
	$stmt->execute();
	
	$row = $stmt->get_result()->fetch_object();
	// New article revenue
	echo number_format($row->price * ($row->start + $row->count - $row->finish),2, ",","");
	
	$db->close();
	http_response_code(200);
	
}else{
	//if $col is not valid
	http_response_code(400);
}
?>