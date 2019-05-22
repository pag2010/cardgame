<?php
class Model_Friends extends Model
{
	private $mysqli;
	public $friends;
	public $players;
	public $subscribers;

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

	public function get_data(){	
		
	}

	public function add_friend($subscriber, $player){
		$err=$this->check_connection();
        if ($err!=null){
            return $err;
        }
        $query="INSERT INTO friends set subscriber='".$subscriber."', player='".$player."'";
        if ($result = $this->mysqli->query($query)) {
			return null;
		}else{
			return ("Ошибка при выполнении запроса ".$this->mysqli->error);
		}
	}

	public function get_all_friends($login){
        $err=$this->check_connection();
        if ($err!=null){
            return $err;
		}
        $query="SELECT friends.player FROM friends INNER JOIN friends AS friends1 ON friends.subscriber=friends1.player WHERE friends.subscriber=friends1.player AND friends.player=friends1.subscriber AND friends.subscriber='".$login."'";
        $friends;
        if ($result = $this->mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$friends[]=$row["player"];
			}
			$result->free();
			$this->friends=$friends;
			return null;
		}else{
			return ("Ошибка при выполнении запроса1 ".$this->mysqli->error);
		}
	}

	public function get_paged_friends($login, $page, $count){
        $err=$this->check_connection();
        if ($err!=null){
            return $err;
		}
		$start=$page*$count;
        $query="SELECT friends.player FROM friends INNER JOIN friends AS friends1 ON friends.subscriber=friends1.player WHERE friends.subscriber=friends1.player AND friends.player=friends1.subscriber AND friends.subscriber='".$login."' LIMIT ".$start.", ".$count;
        $friends;
        if ($result = $this->mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$friends[]=$row["player"];
			}
			$result->free();
			$this->friends=$friends;
			return null;
		}else{
			return ("Ошибка при выполнении запроса1 ".$this->mysqli->error);
		}
	}
	
	public function get_all_players($login){
        $err=$this->check_connection();
        if ($err!=null){
            return $err;
		}
        $query="SELECT player FROM friends WHERE subscriber='".$login."'";
        $players;
        if ($result = $this->mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$players[]=$row["player"];
			}
			$result->free();
			$this->players=$players;
			return null;
		}else{
			return ("Ошибка при выполнении запроса1 ".$this->mysqli->error);
		}
	}

	public function get_paged_players($login, $page, $count){
        $err=$this->check_connection();
        if ($err!=null){
            return $err;
		}
		$start=$page*$count;
		$query="SELECT player FROM friends WHERE subscriber='".$login."' LIMIT ".$start.", ".$count;
		//$query="SELECT player FROM friends WHERE subscriber='".$login."' LIMIT 0, ".$count;
        $players;
        if ($result = $this->mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$players[]=$row["player"];
			}
			$result->free();
			$this->players=$players;
			return null;
		}else{
			return ("Ошибка при выполнении запроса1 ".$this->mysqli->error);
		}
	}
	
	public function get_all_subscribers($login){
        $err=$this->check_connection();
        if ($err!=null){
            return $err;
		}
        $query="SELECT subscriber FROM friends WHERE player='".$login."'";
        $subscribers;
        if ($result = $this->mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$subscribers[]=$row["subscriber"];
			}
			$result->free();
			$this->subscribers=$subscribers;
			return null;
		}else{
			return ("Ошибка при выполнении запроса1 ".$this->mysqli->error);
		}
	}
	
	public function del_subscr($user, $login){
        $err=$this->check_connection();
        if ($err!=null){
            return $err;
		}
        $query="DELETE from friends where subscriber='".$user."' and player='".$login."'";
        if ($result = $this->mysqli->query($query)) {
			return null;
		}else{
			return ("Ошибка при выполнении запроса1 ".$this->mysqli->error);
		}
    }

	public function close_connection(){
		$this->mysqli->close();
	}

	public function set_data($chat_id, $sender, $msg){
		
	}
}