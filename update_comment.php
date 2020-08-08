<?php

$comment = $_GET['comment'];

require "db.php";
	
	// Update comment in current_abrechnung
	// Prepare statement
	if (!($stmt = $db->prepare("UPDATE current_abrechnung SET comment = ?"))) {
    echo "Prepare failed: (" . $db->errno . ") " . $db->error;
	}
	// Bind
	if(!$stmt->bind_param("s", $comment)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	// Execute
	if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}

?>