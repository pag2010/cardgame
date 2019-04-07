<?php
    session_start();
    require_once("error_handler.php");
    class Controller_Chat extends Controller
    {
        function __construct()
        {
            $this->model=new Model_Chat();
            $this->storage=new Storage_Chat($_SESSION['login'], $this->model);
            $this->view=new View();
        }

	    function action_index()
	    {
            if (isset($_SESSION['login'])){
                $login=$_SESSION['login'];
                if (isset($_GET['open_chat'])){
                    $chat_id=$_GET['open_chat'];
                    if (isset($_SESSION['chat'.$chat_id])){
                        $this->model->messages=$_SESSION['chat'.$chat_id];
                    }else{
                        //$err=$this->model->get_msg($chat_id);
                        $err=$this->storage->get_msg($chat_id);
                        if ($err!=null){
                            ErrorHandler::addError($err);
                            ErrorHandler::displayErrors();
                            return;
                        }
                        $_SESSION['chat'.$chat_id]=$this->model->messages; 
                    }
                    if (count($this->model->messages['sender'])>0){
                        for ($i=0;$i<count($this->model->messages['sender']);$i++){
                            if ($this->model->messages['sender'][$i]!=$login){
                                echo $this->model->messages['sender'][$i].": ".$this->model->messages['message'][$i].'</br>';
                            }else{
                                echo "you: ".$this->model->messages['message'][$i].'</br>';
                            }
                        }
                    }else{
                        echo "У вас пока нет сообщений.";
                    }
                    if (isset($_POST['submit'])){
                        $err=$this->model->add_msg($chat_id, $_SESSION['login'], $_POST['msg']);
                        if ($err!=null){
                            ErrorHandler::addError($err);
                            ErrorHandler::displayErrors();
                            return;
                        }
                        $_SESSION['chat'.$chat_id]['sender'][]=$_SESSION['login'];
                        $_SESSION['chat'.$chat_id]['message'][]=$_POST['msg'];
                        echo '<meta http-equiv="refresh" content="0;URL=/chat?open_chat='.$chat_id.'">';
                    }
                    $this->view->generate('chat/opened_chat_view.php', 'template_view.php');
                }
                else{
                    $this->view->generate('chat/info_chat_view.php', 'template_view.php');
                    $this->model->get_chat($_SESSION['login']);
                    $arr=$this->model->chat_id;
                    for($i=0; $i<count($arr); $i++){
                        echo "<input name='open_chat' type='submit' value='".$arr[$i]."'>";
                    }
                    echo '</form>';
                }
            }else{
                echo '<meta http-equiv="refresh" content="0;URL=/auth/login">';
            }
        }
        function action_create()
        {
            $this->view->generate('chat/create_chat_view.php', 'template_view.php');
            if (isset($_POST['submit'])){
                $err=$this->storage->add_chat($_POST['login']);
                if ($err!=null){
                    ErrorHandler::addError($err);
                    ErrorHandler::displayErrors();
                    return;
                }
                
            }
            
        }
    }
?>