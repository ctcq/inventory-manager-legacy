<?php
	$dbhost = 'localhost';
	$dbname = 'bar_inventar';
	$user = 'guest';
	$pw = '1234';
	
	$db = new mysqli($dbhost, $user, $pw, $dbname);
	$db->set_charset("utf8");
?>