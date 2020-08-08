<?php
	require "db_guest.php";
	
	//get data for table-name from current_abrechnung
	$query = <<<eof
				SELECT timestamp, name FROM current_abrechnung
				ORDER BY id DESC LIMIT 1
eof;

	//Crate named table in archive schema from current
	$data = $db->query($query)->fetch_object();
	$name = trim($data->timestamp . "_" . $data->name);

	$name = trim($name);
	$name = str_replace(":", "-", $name);

	$query = <<<eof
				CREATE TABLE last AS SELECT * FROM current
eof;
	$db->query($query);

	// Insert into sessions_finished
    $query = "CALL bar_inventar.insert_into_all()";
    $db->query($query);

    //Export data as csv file
    include "export.php";

	// Reset the db
	$query = "CALL bar_inventar.finish_list()";
	$db->query($query);
	
	$db->close();

?>