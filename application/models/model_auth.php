<?php
class Model_Login extends Model
{
	public $login;
	public $password_hash;
	public $session_hash;
	public $email;

	public function connect_to_data()
	{
		require_once 'connection/connection.php';
		$link = mysqli_connect($host, $user, $password, $database);
		return $link;
	}
	public function get_data()
	{	
		$link=$this->connect_to_data();
		$login=htmlentities(mysqli_real_escape_string($link, $this->login));
		$query="SELECT email, password_hash, session_hash from user_auth where login='".$login."'";
		$result=mysqli_query($link, $query) or die ("Ошибка ".mysqli_error($link));
		if ($result){
			$data = mysqli_fetch_assoc($result);
			$this->password_hash=$data['password_hash'];
			$this->email=$data['email'];
			$this->session_hash=$data['session_hash'];
			mysqli_free_result($result);
			mysqli_close($link);
			return null;
		}else{
			echo "closed";
			mysqli_free_result($result);
			mysqli_close($link);
			return false;
		}
	}

}