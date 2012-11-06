<?php

class Messages {

	private $chatID;
	private $user;
	private $previousFetchTime;
	
	public function __construct($chID, $usr, $prevFetch){
		$this->chatID($chID);
		$this->user($usr);
		$this->previousFetchTime($prevFetch);
	}
	
	public function chatID(){
		return $this->chatID;
	}
	
	public function setChatID($value){
		$this->chatID = $value;
	}
	
	public function user(){
		return $this->user;
	}
	
	public function setUser($value){
		$this->user = $value;
	}
	
	public function previousFetchTime(){
		return $this->previousFetchTime;
	}
	
	public function setPreviousFetchTime($value){
		$this->previousFetchTime = $value;
	}
	
	public function getNewMessages(){
		return getNewMessagesS($this->chatID, $this->user, $this->previousFetchTime);
	}
	
	public static function getNewMessagesS($chatID, $user, $prevFetchTime){
		include('connect.php');
		$sql = "SELECT * FROM messages WHERE chat_id = " . $chatID . " AND time >= '" . $prevFetchTime . "' AND author != " . $user;
		
		//echo $sql;
		$result = mysql_query($sql);
		$messages = array();
		while($row = mysql_fetch_array($result)){
			$messages[] = $row;
		}
		mysql_close();
		return $messages;
	}
	
	public static function checkForNewMessagesS($chatID){
		include('connect.php');
		$sql = "UPDATE chats SET `new` = 0 WHERE id = $chatID AND `new` = 1";
		//echo $sql . ' --';
		mysql_query($sql);
		$affected = mysql_affected_rows();
		mysql_close();
		return ($affected) ? true : false;
	}
	
	public static function storeMessage($chatID, $author, $msg){
		include('connect.php');
		$sql = "INSERT INTO messages (chat_id, msg, author) VALUES ($chatID, '$msg', $author)";
		mysql_query($sql);
		//echo $sql;
		$sql = "UPDATE chats SET `new` = 1 WHERE id = $chatID";
		mysql_query($sql);
		mysql_close();
	}

}

?>