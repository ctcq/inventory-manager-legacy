<?php
	// Returns whether finished and started sessions have the same length or not
	require "db_guest.php";
	
	$query = "SELECT timestamp FROM sessions_finished";
	$result = $db->query($query);
	$finished_count = mysqli_num_rows($result);
	
	$query = "SELECT timestamp FROM sessions_started";
	$result = $db->query($query);
	$started_count = mysqli_num_rows($result);
	
	$ongoing = !($started_count === $finished_count);
	$db->close();
?>