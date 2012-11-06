<?php

	include('closeChat.php');
	
	require_once('classFb.php');
	
	$fbid = fb::userID();
		
	$usr = new User();
	$usrk = false; //new or existing user, false: new
		
		if($fbid != 0){
			if($usrk = User::getUserByID($fbid)){
				$usr = $usrk;
			} else {
				$usr->setUserID($fbid);
			}
			
			$usr->setisFB(true);
			$usr->setUsername('abc');
			$usr->setAvailability(true);
			$usr->setChatID(0);
		
			$_SESSION['userID'] = $usr->userID();
			$_SESSION['prevTime'] = date('Y-m-d H:i:s');
			echo $fbid;
			if(! $usrk)
				$usr->storeUser();
			else
				$usr->updateUser();
			
		}

?>