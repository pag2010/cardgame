<?php
class Model_Chat extends Model
{
	public $chat_id;
	public $login1;
	public $login2;
	public $messages;
	public $message;
	private $mysqli;

	function __construct(){
		/*require_once 'application/models/connection/connection.php';
		$this->mysqli = new mysqli($host, $user, $password, $database);*/
	}

	public function connect(){
		include 'application/models/connection/connection.php';
		$this->mysqli = new mysqli($host, $user, $password, $database);
	}

	public function check_connection(){
		$this->connect();
		if ($this->mysqli->connect_errno) {
			return ("Ошибка соединения: %s\n".$this->mysqli->connect_error);
		}
		return null;
	}
	public function connect_to_data(){
	
	}

	public function get_chat($login){
		$err=$this->check_connection();
		if ($err!=null){
			return $err;
		}
		$query="SELECT id, login1, login2 from chats where login1='".$login."' or login2='".$login."'";
		if ($result = $this->mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$this->chat_id[]=$row["id"];
				$this->login1[]=$row["login1"];
				$this->login2[]=$row["login2"];
			}
			//$this->char_id=$char_id;
			$result->free();
			//$this->mysqli->close();
			return null;
		}else{
			//$this->mysqli->close();
			return ("Ошибка при выполнении запроса1 ".$this->mysqli->error);
		}
	}

	public function get_data(){	
		
	}

	public function get_msg($chat_id){	
		$err=$this->check_connection();
		if ($err!=null){
			return $err;
		}
		$query="SELECT sender_login, message from messages where chat_id=".$chat_id;
		if ($result = $this->mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$login[]=$row["sender_login"];
				$message[]=$row["message"];
			}
			$this->messages["sender"]=$login;
			$this->messages["message"]=$message;
			$result->free();
			//$this->mysqli->close();
			return null;
		}else{
			//$this->mysqli->close();
			return ("Ошибка при выполнении запроса");
		}
	}

	public function add_msg($chat_id, $sender, $msg){
		$err=$this->check_connection();
		if ($err!=null){
			return $err;
		}
		$query="INSERT INTO messages set chat_id=".$chat_id.", sender_login='".$sender."', message='".$msg."'";
		if ($result = $this->mysqli->query($query)) {
			////$this->mysqli->close();
			return null;
		}else{
			////$this->mysqli->close();
			return ("Ошибка при выполнении запроса");
		}
	}

	public function add_chat($login1, $login2){
		$err=$this->check_connection();
		if ($err!=null){
			return $err;
		} 	
		$query="INSERT INTO chats set login1="."'".$login1."', login2='".$login2."'";
		if ($result = $this->mysqli->query($query)) {
			////$this->mysqli->close();
			return null;
		}else{
			////$this->mysqli->close();
			return ("Ошибка при выполнении запроса ".$this->mysqli->error);
		}
	}

	public function close_connection(){
		$this->mysqli->close();
	}

	public function set_data($chat_id, $sender, $msg){
		
	}
}