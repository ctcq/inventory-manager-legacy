<?php

	$dbhost = 'localhost';
	$dbname = 'bar_inventar';
	$user = '';
	$pw = '';
	
	$db = new mysqli($dbhost, $user, $pw, $dbname);
	$db->set_charset("utf8");
?>
