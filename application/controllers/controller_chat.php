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
                    $err=$this->storage->get_msg($chat_id);
                        if ($err!=null){
                            ErrorHandler::addError($err);
                            ErrorHandler::displayErrors();
                            return;
                        }
                    $this->view->generate('chat/opened_chat_view.php', 'template_view.php','opened_chat_js.php', 'opened_chat_css.php', $this->model->messages);
                }
                else{
                    $this->model->get_chat($_SESSION['login']);
                    $chats=$this->model->chat_id;
                    $login1=$this->model->login1;
                    $login2=$this->model->login2;
                    $arr['title']="Чат";
                    $arr['login']=$_SESSION['login'];
                    $arr['chats']=$chats;
                    $arr['login1']=$login1;
                    $arr['login2']=$login2;
                    $this->view->generate('chat/info_chat_view.php', 'template_view.php', 'info_chat_js.php', 'info_chat_css.php', $arr);
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

        function action_send(){
            if (isset($_POST['submit'])){
                $err=$this->model->add_msg($_POST['chat_id'], $_SESSION['login'], $_POST['msg']);
                if ($err!=null){
                    ErrorHandler::addError($err);
                    ErrorHandler::displayErrors();
                    return;
                }
            }
        }
    }
?>