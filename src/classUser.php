<?php

class User{

	private $userID;
	private $username;
	private $status;	//true: available; false: unavailable
	private $chatID;
	private $isFB;
	
	public function __construct(){
	
	}
	
	public function userID(){
		return $this->userID;
	}
	
	public function setUserID($value){
		$this->userID = $value;
	}
	
	public function chatID(){
		return $this->chatID;
	}
	
	public function setChatID($value){
		$this->chatID = $value;
	}
	
	public function username(){
		return $this->username;
	}
	
	public function setUsername($value){
		$this->username = $value;
	}
	
	public function isFB(){
		return $this->isFB;
	}
	
	public function setisFB($value){
		$this->isFB = $value;
	}
	
	public function isAvailable(){
		return $this->status;
	}
	
	public function setAvailability($value){
		$this->status = $value;
	}
	
	public static function generateID(){
		 include('connect.php');
		 $id = 0;
		 do{
			
			$id = mt_rand();
			
			$result = mysql_query("SELECT id from users where id = $id");
			$row = mysql_fetch_array($result);
			
		 }while($row);
		 mysql_close();
		 return $id;
	}
	
	public function storeUser(){
		include('connect.php');
		$status = ($this->status)? 'available' : 'engaged';
		$isFB = ($this->isFB) ? 1 : 0;
		$sql = "INSERT INTO users (id, username, status, chat_id, isFB) VALUES (" . $this->userID . ", '" . $this->username . "', '$status', " . $this->chatID . ", $isFB )";
		//echo $sql;
		mysql_query($sql);
		mysql_close();
	}
	
	public function updateUser(){
		include('connect.php');
		$status = ($this->status)? 'available' : 'engaged';
		$isFB = ($this->isFB) ? 1 : 0;
		$sql = "UPDATE users SET id = " . $this->userID . ", username = '" . $this->username . "', status = '$status', isFB='$isFB', chat_id = " . $this->chatID . " WHERE id = " . $this->userID;
		//echo $sql;
		mysql_query($sql);
		mysql_close();
	}
	
	public function refreshUser(){
		include('connect.php');
		$sql = "SELECT * FROM users WHERE id = " . $this->userID;
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		$this->username = $row['username'];
		$this->status = ($row['status'] == 'available') ? true : false;
		$this->chatID = $row['chat_id'];
		$this->isFB = ($row['isFB'] == 0)? false : true;
	}
	
	public function deleteUser(){
		include('connect.php');
		$sql = "DELETE FROM users WHERE id = " . $this->userID;
		mysql_query($sql);
		mysql_close();
	}
	
	public static function getAvailableUser($me){
		$sql = "SELECT * FROM users WHERE status = 'available' AND id != $me LIMIT 0, 1";
		return User::getUser($sql);
	}
	
	public static function getUserByID($userID){
		$sql = "SELECT * FROM users WHERE id = " . $userID;
		return User::getUser($sql);
	}
	
	private static function getUser($sql){
		include('connect.php');
		//$sql = "SELECT * FROM users WHERE id = " . $userID;
		//echo $sql;
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		mysql_close();
		
		if($row){
			
			$usr = new User();
			$usr->setUserID($row['id']);
			$usr->setUsername($row['username']);
			$usr->setAvailability(($row['status'] == 'available') ? true: false);
			$usr->setChatID($row['chat_id']);
			$usr->isFB = ($row['isFB'] == 0)? false : true;
			return $usr;
		} else {
			return false;
		}
	}

}

?>