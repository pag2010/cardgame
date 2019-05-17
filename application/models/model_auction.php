<?php
class Model_Auction extends Model
{
	private $mysqli;
    public $auction_list;
    public $money;
    public $member;

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
        $query="SELECT *, auction_cards.id AS auction_id  FROM auction_cards INNER JOIN cards ON auction_cards.card_id=cards.id";
        if ($result = $this->mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
                $err=$this->get_max_price($row['auction_id']);
                if ($err!=null){
                    return $err;
                }
                $err=$this->get_member_max_price($row['auction_id'], $this->money);
                if ($err!=null){
                    return $err;
                }
                //echo $this->money;
				$card=new Card($row["card_id"], $row["title"], $row["description"], $row["rarity_title"], $row["mana_cost"], $row["life"], $row["attack"], $row["kind"]);
                $auction_item=new Auction_Item($row["auction_id"], $row["seller"] ,$this->member, $card, $row["quantity"], $row["start_price"],$this->money, $row["start_date"], $row["sell_date"]);
                $this->auction_list[]=$auction_item;
                echo $row["auction_cards.id"];
            }
			$result->free();
			return null;
		}else{
			return ("Ошибка при выполнении запроса1 ".$this->mysqli->error);
		}
    }

    public function get_max_price($id){
        $err=$this->check_connection();
        if ($err!=null){
            return $err;
        }
        $query="SELECT MAX(price) AS price FROM auction_queue WHERE auction_id=".$id;
        if ($result = $this->mysqli->query($query)) {
			$row = $result->fetch_assoc();
            $this->money=$row['price'];
			$result->free();
			return null;
		}else{
			return ("Ошибка при выполнении запроса1 ".$this->mysqli->error);
		}
    }

    public function get_member_max_price($id, $price){
        $err=$this->check_connection();
        if ($err!=null){
            return $err;
        }
        if (isset($price)){
            $query="SELECT member FROM auction_queue WHERE auction_id=".$id." and price=".$price;
            if ($result = $this->mysqli->query($query)) {
                $row = $result->fetch_assoc();
                $this->member=$row['member'];
                
                $result->free();
                return null;
            }else{
                return ("Ошибка при выполнении запроса1 ".$this->mysqli->error);
            }
        }else{
            $this->member=null;
            return null;
        }
    }

    public function add_item($auction_item){
        $err=$this->check_connection();
        if ($err!=null){
            return $err;
        }
        $query="INSERT INTO auction_cards values (0, '".$auction_item->seller."', ".$auction_item->card->id.", ".$auction_item->quantity.", ".$auction_item->start_price.", '".$auction_item->start_date."', '".$auction_item->sell_date."')";
        if ($result = $this->mysqli->query($query)) {
			return null;
		}else{
			return ("Ошибка при выполнении запроса ".$this->mysqli->error);
        }
        //echo $auction_item->seller;
    }

    public function change_price($id, $price, $login){
        $err=$this->check_connection();
        if ($err!=null){
            return $err;
        }
        //$query="UPDATE auction_cards set sell_price=".$price.", buyer='".$login."' where id=".$id;
        $query="INSERT into auction_queue values(0, ".$id.", '".$login."', ".$price.")";
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