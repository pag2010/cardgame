<?php
class Model_Login extends Model
{
	public $login;
	public $password_hash;
	public $session_hash;
	public $email;
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
		$login=htmlentities($this->mysqli->real_escape_string($this->login));
		$query="SELECT email, password_hash, session_hash from user_auth where login='".$login."'";
		if ($result = $this->mysqli->query($query)) {
			if ($result->num_rows==0){
				return "Пользователь не найден";
			}
			$data=$result->fetch_assoc();
			$this->password_hash=$data['password_hash'];
			$this->email=$data['email'];
			$this->session_hash=$data['session_hash'];
			$result->free();
			$this->mysqli->close();
			return null;
		}else{
			$this->mysqli->close();
			return ("Ошибка при выполнении запроса");
		}
	}

	public function set_data(){
		$err=$this->check_connection();
		if ($err!=null){
			return "Ошибка при соединении с базой данных ".$err;
		}
		$login=htmlentities($this->mysqli->real_escape_string($this->login));
		$query="SELECT login from user_auth where login='".$login."'";
		if ($result = $this->mysqli->query($query)){
			if ($result->num_rows>0){
				//echo "Пользователь уже существует";
				return "Пользователь уже существует";
			}
		}
		$result->free();
		$password_hash=htmlentities($this->mysqli->real_escape_string($this->password_hash));
		$email=htmlentities($this->mysqli->real_escape_string($this->email));
		$query="INSERT INTO user_auth SET login='".$login."', password_hash='".$password_hash."', email='".$email."', session_hash=''";
		if ($result = $this->mysqli->query($query)){
			//echo "Пользователь создан";
			$this->mysqli->close();
			return null;
		}else{
			//echo "Пользователь НЕ создан ".$this->mysqli->error;
			$this->mysqli->close();
			return "Ошибка. Пользователь не создан ".$this->mysqli->error;
		}
	}
}