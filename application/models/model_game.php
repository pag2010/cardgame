<?php
class Model_Game extends Model
{
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

	public function set_data(){
		
	}

	public function get_collection($login){
		$err = $this->check_connection();
		if ($err != null) {
			return $err;
		}
		$query="SELECT title FROM cards c, collections coll WHERE c.id=coll.card_id AND coll.login='".$login."'";
		if ($result = $this->mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$this->collection_card[]=$row["title"];
			}
			$result->free();
			return null;
		} else{
			return("Ошибка при выполнении запроса ".$this->mysqli->error);
		}
	}
}