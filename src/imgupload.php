<?php

require_once('classMessages.php');
require_once('classUser.php');

session_start();

if (!isset($_SESSION['userID']))
	return;
	
if ($_FILES["file"]["error"] > 0)
{
	echo "Error: " . $_FILES["file"]["error"] . "<br />";
}
else
{
	$usr = User::getUserByID($_SESSION['userID']);
	if($usr->isAvailable())
		return;
	
	$fn = generateFileName();
	move_uploaded_file($_FILES["file"]["tmp_name"], $fn);
	
	Messages::storeMessage($usr->chatID(), $usr->userID(), "img:" . basename($fn));
	
	echo basename($fn);
}

function generateFileName(){
	return "upload/" . mt_rand() . "." . end(explode("." , $_FILES["file"]["name"]));
}

?>