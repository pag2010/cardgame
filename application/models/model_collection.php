<?php
class Model_Collection extends Model
{
	public $cards;
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

	public function get_all($user, $page, $count){
        $err=$this->check_connection();
        if ($err!=null){
            return $err;
		}
		$start=$page*$count;
        $query="SELECT card_id, title, description, rarity_title, mana_cost, life, attack, `kind`, quantity FROM collections inner join cards ON collections.card_id=cards.id where login='".$user."' LIMIT ".$start.", ".$count;
        if ($result = $this->mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$card=new Card($row["card_id"], $row["title"], $row["description"], $row["rarity_title"], $row["mana_cost"], $row["life"], $row["attack"], $row["kind"], $row["quantity"]);
				$this->cards[]=$card;
			}
			$result->free();
			return null;
		}else{
			return ("Ошибка при выполнении запроса1 ".$this->mysqli->error);
		}
    }

	public function connect_to_data(){
	
	}
	public function get_data(){	
		
	}

	public function set_data(){
		
	}

	public function close_connection(){
		$this->mysqli->close();
	}
}

class Card{
    public $id;
    public $title;
    public $description;
    public $rarity_title;
    public $mana_cost;
    public $life;
    public $attack;
	public $kind;
	public $quantity;

    function __construct($id, $title, $description, $rarity_title, $mana_cost, $life, $attack, $kind, $quantity){
        $this->id=$id;
        $this->title=$title;
        $this->description=$description;
        $this->rarity_title=$rarity_title;
        $this->mana_cost=$mana_cost;
        $this->life=$life;
        $this->attack=$attack;
        $this->kind=$kind;
		$this->quantity=$quantity;
	}
}