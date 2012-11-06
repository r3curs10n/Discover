<?php

//require_once('classMessages.php');
require_once('classUser.php');
require_once('classChat.php');

	session_start();
	
	if(isset($_SESSION['userID'])){
	
		$usr = User::getUserByID($_SESSION['userID']);
		
		if(! $usr){
			unset($_SESSION['userID']);
			return;
		}
		
		if($usr->isAvailable()){
			if(! $usr->isFB()){
				$usr->deleteUser();
			}
		}else{
			
			$chat = Chat::getChatByID($usr->chatID());
			
			if($chat){
				$chat->deleteChat();
				$chat->deleteHistory();
				if(! $usr->isFB()){
					$usr->deleteUser();
				}else{
					$usr->setAvailability(true);
					$usr->updateUser();
				}
				//echo "--" . $chat->user1() . "-" . $chat->user2() . "--";
				if($chat->user1() == $usr){
					$friend = User::getUserByID($chat->user2());
				} else {
					$friend = User::getUserByID($chat->user1());
				}
				if($friend){
					$friend->setAvailability(true);
					$friend->updateUser();
				}
			}
			
		}
		unset($_SESSION['userID']);
	}

?>