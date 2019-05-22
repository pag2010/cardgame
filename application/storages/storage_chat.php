<?php
    class Storage_Chat
    {
	    public $chat_id;
        public $login;
        public $model;
        
        function __construct($login, $model){
            $this->model= $model;
            $this->login=$login;
        }

        public function get_msg($chat_id){
            $err=$this->model->get_chats($this->login);
            if ($err!=null){
                $this->model->close_connection();
                return $err;
            }
            $chats=$this->model->chat_id;
            $flag=false;
            foreach($chats as $c){
                if ($c==$chat_id){
                    $flag=true;
                }
            }
            if (!$flag){
                $this->model->close_connection();
                return "Диалог не найден :(";
            }
            $err=$this->model->get_msg($chat_id);
            if ($err!=null){
                $this->model->close_connection();
                return $err;
            }
            $this->model->close_connection();
        }

        public function add_chat($login){
            $err=$this->model->get_chats($this->login);
            if ($err!=null){
                $this->model->close_connection();
                return $err;
            }
            $login1=$this->model->login1;
            $login2=$this->model->login2;
            $flag=true;
            for ($i=0;$i<count($login1);$i++){
                if ($login1[$i]==$login || $login2[$i]==$login){
                    $flag=false;
                }
            }
            if (!$flag){
                $this->model->close_connection();
                return "У вас уже существует диалог с этим пользователем";
            }
            $err=$this->model->add_chat($this->login, $login);
            if ($err!=null){
                $this->model->close_connection();
                return $err;
            }
            $this->model->close_connection();
        }
    }
?>