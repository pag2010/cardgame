<?php
    class Storage_Friends
    {
	    public $player;
        public $login;
        public $model;
        
        function __construct($login, $model){
            $this->model= $model;
            $this->login=$login;
        }

        public function add_friend($player){
            $err=$this->model->get_all_friends($this->login);
            if ($err!=null){
                $this->model->close_connection();
                return $err;
            }
            $flag=true;
            $friends=$this->model->friends;
            foreach ($friends as $fr){
                echo $fr." ".$player."</br>";
                if (strcmp($fr, $player)==0){
                    $flag=false;
                }
            }
            if (!$flag){
                $err="Вы уже подписаны на этого пользователя";
                $this->model->close_connection();
                return $err;
            }else{
                $err=$this->model->add_friend($this->login, $player);
                $this->model->close_connection();
                if ($err!=null){
                    return $err;
                }
                return null;
            }

        }
    }
?>