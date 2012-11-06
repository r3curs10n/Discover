<?php

class Chat{

	private $chatID;
	private $user1;
	private $user2;
	private $isNewMessage;
	
	public function __construct(){
	
	}
	
	public function chatID(){
		return $this->chatID;
	}
	
	public function setChatID($value){
		$this->chatID = $value;
	}
	
	public function user1(){
		return $this->user1;
	}
	
	public function setUser1($value){
		$this->user1 = $value;
	}
	
	public function isNewMessage(){
		return $this->isNewMessage;
	}
	
	public function setIsNewMessage($value){
		$this->isNewMessage = $value;
	}
	
	public function user2(){
		return $this->user2;
	}
	
	public function setUser2($value){
		$this->user2 = $value;
	}
	
	public static function generateID(){
		return mt_rand();
	}
	
	public function storeChat(){
		include('connect.php');
		$sql = "INSERT INTO chats (id, user1, user2, `new`) VALUES (" . $this->chatID . ", " . $this->user1 . ", " . $this->user2 . ", " . $this->isNewMessage . ")";
		//echo $sql;
		mysql_query($sql);
		mysql_close();
	}
	
	public function deleteChat(){
		include('connect.php');
		$sql = "DELETE FROM chats WHERE id = " . $this->chatID;
		mysql_query($sql);
		mysql_close();
	}
	
	public function deleteHistory(){
		include('connect.php');
		$sql = "DELETE FROM messages WHERE chat_id = " . $this->chatID;
		mysql_query($sql);
		mysql_close();
	}
	
	public static function getChatByID($chatID){
		include('connect.php');
		$sql = "SELECT * FROM chats WHERE id = $chatID";
		//echo $sql;
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		mysql_close();
		if($row){
			$chat = new Chat();
			$chat->setChatID($row['id']);
			$chat->setUser1($row['user1']);
			$chat->setUser2($row['user2']);
			return $chat;
		} else {return false;}
	}

}

?>