<?php
class Model_Chat extends Model
{
	public $chat_id;
	public $login1;
	public $login2;
	private $mysqli;

	function __construct(){
		require_once 'application/models/connection/connection.php';
		$this->mysqli = new mysqli($host, $user, $password, $database);
	}

	public function check_connection(){
		if ($this->mysqli->connect_errno) {
			return ("Ошибка соединения: %s\n".$this->mysqli->connect_error);
		}
		return null;
	}

	public function connect_to_data(){
	
	}
	public function get_data(){	
		$err=$this->check_connection();
		if ($err!=null){
			return $err;
		}
		$query="SELECT id, login1, login2 from chats where login1='".$_SESSION['login']."' or login2='".$_SESSION['login']."'";
		if ($result = $this->mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$this->chat_id[]=$row["id"];
				$this->login1[]=$row["login1"];
				$this->login2[]=$row["login2"];
			}
			//$this->char_id=$char_id;
			$result->free();
			$this->mysqli->close();
			return null;
		}else{
			$this->mysqli->close();
			return ("Ошибка при выполнении запроса");
		}
	}

	public function set_data(){
		
	}
}