<?php
	$dbhost = 'localhost';
	$dbname = 'bar_inventar';
	$user = 'flushbot';
	$pw = '1234';
	
	$db = new mysqli($dbhost, $user, $pw, $dbname);
	$db->set_charset("utf8");
?>