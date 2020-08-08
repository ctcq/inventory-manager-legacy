<?php
	$dbhost = 'localhost';
	$dbname = 'bar_inventar';
	$user = 'root';
	$pw = '';
	
	$db = new mysqli($dbhost, $user, $pw, $dbname);
	$db->set_charset("utf8");

	$result =$db->query("SELECT * FROM current")->fetch_assoc();
	echo count($result);
?>