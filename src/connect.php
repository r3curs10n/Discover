<?php

	$hostname = 'localhost';
	$username = 'root';
	$password = '';

	$link = mysql_connect($hostname, $username, $password);
	 
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	
	mysql_select_db("discover",$link);

?>