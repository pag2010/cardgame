<?php
class Model_Auction extends Model
{
	private $mysqli;
	public $auction_list;

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
    
    public function get_all_auction(){
        $err=$this->check_connection();
        if ($err!=null){
            return $err;
        }
        $query="SELECT * FROM auction_cards INNER JOIN cards ON auction_cards.card_id=cards.id";
        if ($result = $this->mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$card=new Card($row["card_id"], $row["title"], $row["description"], $row["rarity_title"], $row["mana_cost"], $row["life"], $row["attack"], $row["kind"]);
                $auction_item=new Auction_Item($row["id"], $row["seller"],$row["buyer"], $card, $row["quantity"], $row["start_price"],$row["sell_price"], $row["start_date"], $row["sell_date"]);
                $this->auction_list[]=$auction_item;
            }
			$result->free();
			return null;
		}else{
			return ("Ошибка при выполнении запроса1 ".$this->mysqli->error);
		}
    }

    public function add_item($auction_item){
        $err=$this->check_connection();
        if ($err!=null){
            return $err;
        }
        $query="INSERT INTO auction_cards values (0, ".$auction_item->seller.", null, ".$auction_item->card->id.", ".$auction_item->quantity.", ".$auction_item->start_price.", null, ".$auction_item->start_date.", ".$auction_item->sell_date.")";
        if ($result = $this->mysqli->query($query)) {
			return null;
		}else{
			return ("Ошибка при выполнении запроса ".$this->mysqli->error);
		}
    }

	public function close_connection(){
		$this->mysqli->close();
	}

	public function set_data($chat_id, $sender, $msg){
		
	}
}

class Auction_Item{
    public $id;
    public $seller;
    public $buyer;
    public $card;
    public $quantity;
    public $start_price;
    public $sell_price;
    public $start_date;
    public $sell_date;

    function __construct($id, $seller, $buyer, $card, $quantity, $start_price, $sell_price, $start_date, $sell_date){
        $this->id=$id;
        $this->seller=$seller;
        $this->buyer=$buyer;
        $this->card=$card;
        $this->quantity=$quantity;
        $this->start_price=$start_price;
        $this->sell_price=$sell_price;
        $this->start_date=$start_date;
        $this->sell_date=$sell_date;
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

    function __construct($id, $title, $description, $rarity_title, $mana_cost, $life, $attack, $kind){
        $this->id=$id;
        $this->title=$title;
        $this->description=$description;
        $this->rarity_title=$rarity_title;
        $this->mana_cost=$mana_cost;
        $this->life=$life;
        $this->attack=$attack;
        $this->kind=$kind;

	}
}