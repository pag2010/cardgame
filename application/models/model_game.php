<?php
class Model_Game extends Model
{
	private $mysqli;
	public $unRead;

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

	public function get_unRead($chat_id, $user){	
		$err=$this->check_connection();
		if ($err!=null){
			return $err;
		}
		$query="SELECT SUM(isRead) AS unRead FROM messages WHERE chat_id=".$chat_id." AND sender_login<>'".$user."'";
		if ($result = $this->mysqli->query($query)) {
			if ($row = $result->fetch_assoc()) {
			$this->unRead=row["unRead"];
			$result->free();
			//$this->mysqli->close();
			return null;
			}else{
				return ("Ошибка при выполнении запроса");
			}
		}else{
			//$this->mysqli->close();
			return ("Ошибка при выполнении запроса");
		}
	}

	public function get_unRead_all($user){	
		$err=$this->check_connection();
		if ($err!=null){
			return $err;
		}
		$query="SELECT SUM(isRead) AS unRead FROM chats INNER JOIN messages ON chats.id=messages.chat_id WHERE (login1='".$user."' OR login2='".$user."') AND sender_login<>'".$user."'";
		if ($result = $this->mysqli->query($query)) {
			if ($row = $result->fetch_assoc()) {
			$this->unRead=$row["unRead"];
			$result->free();
			//$this->mysqli->close();
			return null;
			}else{
				return ("Ошибка при выполнении запроса");
			}
		}else{
			//$this->mysqli->close();
			return ("Ошибка при выполнении запроса");
		}
	}

	public function connect_to_data(){
	
	}
	public function get_data(){	
		
	}

	public function set_data(){
		
	}
}