<?php

require_once('classMessages.php');
require_once('classUser.php');

	session_start();

	
	if($_POST['msg'] !='' && isset($_SESSION['userID'])){
	
		$usr = User::getUserByID($_SESSION['userID']);
		
		Messages::storeMessage($usr->chatID(), $usr->userID(), $_POST['msg']);
	}

?>