<?php

	
	$connect = mysql_connect('localhost', 'thelifestream', 'passwerdz');
	
	if (!$connect) {
		die("Couldn't connect.");
	}
	
	$table = mysql_select_db('thelifestream');
	
	if (!$table) {
		die("Couldn't connect.");
	}
	
?>
