<?php
	if(!isset($_POST['name'])){
		header('Location: login.php');
	}else{
	
	require "db.php";
	
	// Create entry in session table
	// Prepare statement
	if (!($stmt = $db->prepare("INSERT INTO sessions_started(name, timestamp) VALUES (?, CURRENT_TIMESTAMP)"))) {
    echo "Prepare failed: (" . $db->errno . ") " . $db->error;
	}
	$name = $_POST['name'];
	// Bind
	if(!$stmt->bind_param("s", $name)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	// Execute
	if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	$stmt->close();

	// Load Preisliste into db
	$file = 'C:/Barlisten/preisliste/preisliste.csv';
	$query = <<<eof
				LOAD DATA INFILE '{$file}'
				INTO TABLE preisliste
				FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
				LINES TERMINATED BY '\n'
				IGNORE 1 LINES
				(article,price,tag)
eof;
	$db->query($query);

	
	//Excecute new liste procedure
        $query = "call bar_inventar.new_list()";
        $db->query($query);

	//Delete old tables from database
        $query = "CALL bar_inventar.drop_all()";
        $db->query($query);

	$db->close();
	
	header("Location: liste.php");
	}
?>