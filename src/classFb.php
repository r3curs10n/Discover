<?php

require_once("fb/facebook.php");

  
class fb{
  
	public static function userID(){
  
		$config = array();
  $config['appId'] = '314321845272806';
  $config['secret'] = '41092253be637c17fe70cddc2d481375';
  $config['fileUpload'] = true; // optional

  $facebook = new Facebook($config);
		
		return $facebook->getUser();
  
	}
  
}

?>