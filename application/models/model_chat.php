<?php
class Model_Chat extends Model
{
	public $chat_id;
	public $login1;
	public $login2;
	public $messages;
	public $message;
	public $chat;
	public $unRead;
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

	public function get_chats($login){
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
			return ("Ошибка при выполнении запроса ".$this->mysqli->error);
		}
	}

	public function get_chats_unread($login){
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
			
			if (isset($this->chat_id)){
				for($i=0; $i<count($this->chat_id); $i++){
				$query="SELECT SUM(isRead) as unRead FROM messages WHERE chat_id=".$this->chat_id[$i]." AND sender_login<>'".$login."'";
				if ($result = $this->mysqli->query($query)) {
					if ($row = $result->fetch_assoc()) {
						$this->unRead[]=$row["unRead"];
					}
				//$this->char_id=$char_id;
				$result->free();
				}
			}
				//$this->mysqli->close();
				return null;
				
			}else{
				return null;
			}
		}else{
			//$this->mysqli->close();
			return ("Ошибка при выполнении запроса ".$this->mysqli->error);
		}
	}

	/*public function get_chats_unread($login){
		$err=$this->check_connection();
		if ($err!=null){
			return $err;
		}
		$query="SELECT chat_id, login1, login2, SUM(isRead) AS unRead FROM chats INNER JOIN messages ON chats.id=messages.chat_id WHERE (login1='".$login."' OR login2='".$login."') AND sender_login<>'".$login."' GROUP BY chat_id";
		if ($result = $this->mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$this->chat_id[]=$row["id"];
				$this->login1[]=$row["login1"];
				$this->login2[]=$row["login2"];
				$this->unRead[]=$row["unRead"];
			}
			//$this->char_id=$char_id;
			$result->free();
			//$this->mysqli->close();
			return null;
		}else{
			//$this->mysqli->close();
			return ("Ошибка при выполнении запроса ".$this->mysqli->error);
		}
	}*/

	public function get_chat($login_user, $login_player){
		$err=$this->check_connection();
		if ($err!=null){
			return $err;
		}
		$query="SELECT id, login1, login2 from chats where login1='".$login_user."' and login2='".$login_player."' or login1='".$login_player."' and login2='".$login_user."'";
		if ($result = $this->mysqli->query($query)) {
			if ($row = $result->fetch_assoc()) {
				$this->chat=new Chat($row["id"], $row["login1"], $row["login2"]);
			}else{
				return "Не удалось найти диалог";
			}
			//$this->char_id=$char_id;
			$result->free();
			//$this->mysqli->close();
			return null;
		}else{
			//$this->mysqli->close();
			return ("Ошибка при выполнении запроса ".$this->mysqli->error);
		}
	}
	

	public function get_data(){	
		
	}

	public function get_msg($chat_id){	
		$err=$this->check_connection();
		if ($err!=null){
			return $err;
		}
		$query="SELECT sender_login, message, date_msg, isRead from messages where chat_id=".$chat_id;
		if ($result = $this->mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$login[]=$row["sender_login"];
				$message[]=$row["message"];
				$date_time[]=$row["date_msg"];
				$isRead[]=$row["isRead"];
			}
			$this->messages["sender"]=$login;
			$this->messages["message"]=$message;
			$this->messages["isRead"]=$isRead;
			$this->messages["date_msg"]=$date_time;
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

	public function read_msg($chat_id, $user){
		$err=$this->check_connection();
		if ($err!=null){
			return $err;
		}
		$query="update messages set isRead=0 where chat_id=".$chat_id." and sender_login<>'".$user."'";
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

	public function del_chat($id){
		$err=$this->check_connection();
		if ($err!=null){
			return $err;
		}
		$query="delete from chats where id=".$id;
		if ($result = $this->mysqli->query($query)) {
			////$this->mysqli->close();
			return null;
		}else{
			////$this->mysqli->close();
			return ("Ошибка при выполнении запроса ".$this->mysqli->error);
		}
	}

	public function get_unRead($chat_id, $user){	
		$err=$this->check_connection();
		if ($err!=null){
			return $err;
		}
		$query="SELECT SUM(isRead) AS unRead FROM messages WHERE chat_id=".$chat_id." AND sender_login<>'".$user."'";
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

	public function close_connection(){
		$this->mysqli->close();
	}

	public function set_data($chat_id, $sender, $msg){
		
	}
}

class Chat {
	public $chat_id;
	public $login1;
	public $login2;

	function __construct($id, $login1, $login2){
		$this->chat_id=$id;
		$this->login1=$login1;
		$this->login2=$login2;
	}
}

?>