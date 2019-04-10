<?php
class Model_Admin extends Model
{
	public $login;
	public $password_hash;
	public $session_hash;
	public $email;
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
	public function get_data(){	
		
	}

	public function set_data($data){
		$err=$this->check_connection();
		if ($err!=null){
			return $err;
		}
		$title=$this->mysqli->real_escape_string($data['title']);
		$description=$this->mysqli->real_escape_string($data['description']);
		//$rariry=$this->mysqli->real_escape_string($data['rarity']);
		$mana_cost=$this->mysqli->real_escape_string($data['mana_cost']);
		$life=$this->mysqli->real_escape_string($data['life']);
		$attack=$this->mysqli->real_escape_string($data['attack']);
		//$kind_id=$this->mysqli->real_escape_string($data['kind']);
		/*$title=$data['title'];
		$description=$data['description'];*/
		$rarity=$data['rarity'];
		/*$mana_cost=$data['mana_cost'];
		$life=$data['life'];
		$attack=$data['attack'];*/
		$kind_id=$data['kind'];
		
		$query="INSERT INTO cards set title='".$title."', description='".$description."', rarity_title='".$rarity."', mana_cost=".$mana_cost.", life=".$life.", attack=".$attack.", kind_id=".$kind_id;
		if ($result = $this->mysqli->query($query)) {
			////$this->mysqli->close();
			return null;
		}else{
			////$this->mysqli->close();
			return ("Ошибка при выполнении запроса ".$this->mysqli->error);
		}
	}

	function get_all_kind(){
		$err=$this->check_connection();
		if ($err!=null){
			return $err;
		}
		$query="SELECT id, title from kind";
		$titles;
		$id;
		$arr;
		if ($result = $this->mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$titles[]=$row["title"];
				$id[]=$row["id"];
			}
			$arr['id']=$id;
			$arr['title']=$titles;
			$result->free();
			return $arr;
		}else{
			return ("Ошибка при выполнении запроса ".$this->mysqli->error);
		}
	}

	function get_all_rarity(){
		$err=$this->check_connection();
		if ($err!=null){
			return $err;
		}
		$query="SELECT title from rarity";
		$titles;
		if ($result = $this->mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$titles[]=$row["title"];
			}
			$result->free();
			return $titles;
		}else{
			return ("Ошибка при выполнении запроса ".$this->mysqli->error);
		}
	}

	public function close_connection(){
		$this->mysqli->close();
	}

}