<?php

require_once('classMessages.php');
require_once('classUser.php');
require_once('classChat.php');

	session_start();
	
	date_default_timezone_set('Asia/Kolkata');
	
	$usr = new User();
	$usrk = false; //new or existing user, false: new
	
	if(!isset($_SESSION['userID'])){
		
		require_once('classFb.php');
		
		$fbid = fb::userID();
		
		if($fbid != 0){
			if($usrk = User::getUserByID($fbid)){
				$usr = $usrk;
			} else {
				$usr->setUserID($fbid);
			}
			$usr->setisFB(true);
		}else{
			$usr->setUserID(User::generateID());
			$usr->setisFB(false);
		}
		$usr->setUsername('abc');
		$usr->setAvailability(true);
		$usr->setChatID(0);
		
		$_SESSION['userID'] = $usr->userID();
		//$_SESSION['status'] = $usr->isAvailable;
		$_SESSION['prevTime'] = date('Y-m-d H:i:s');
		
		if(! $usrk)
			$usr->storeUser();
		else
			$usr->updateUser();
		
	}
	
	$usr->setUserID($_SESSION['userID']);
	$usr->refreshUser(); //echo $usr->chatID();
	
	if($usr->isAvailable()){
		$friend = User::getAvailableUser($usr->userID());
		if($friend){
			$chat = new Chat();
			$chat->setChatID(Chat::generateID());
			$chat->setUser1($usr->userID());
			$chat->setUser2($friend->userID());
			$chat->setIsNewMessage(0);
			
			$usr->setAvailability(false);
			$friend->setAvailability(false);
			$usr->setChatID($chat->chatID());
			$friend->setChatID($chat->chatID());
			
			$chat->storeChat();
			
			$usr->updateUser();
			$friend->updateUser();
			
			$ret['chat'] = true;
		} else {
			$ret['chat'] = false;
		}
	} else { // user is engaged
		
		//echo 'assdfg';
		if(!Chat::getChatByID($usr->chatID())){
			$ret['chat'] = false;
		} else {
			$ret['chat'] = true;
			
			//if(Messages::checkForNewMessagesS($usr->chatID())){
				$ret['messages'] = Messages::getNewMessagesS($usr->chatID(), $usr->userID(), $_SESSION['prevTime']);
				$_SESSION['prevTime'] = date('Y-m-d H:i:s');
			//}
			
		}
		
		
	}
	
	echo json_encode($ret);

?>