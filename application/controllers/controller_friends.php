<?php
    session_start();
    require_once("error_handler.php");
    class Controller_Friends extends Controller
    {
        function __construct()
        {
            $this->model=new Model_Friends();
            $this->storage=new Storage_Friends($_SESSION['login'], $this->model);
            $this->view=new View();
        }

	    function action_index()
	    {
            
            
        }

        function action_add(){
            if (isset($_SESSION['login'])){
                if (isset($_POST['submit'])){
                    $err=$this->storage->add_friend($_POST['login']);
                    if ($err!=null){
                        ErrorHandler::addError($err);
                        ErrorHandler::displayErrors();
                        return;
                    }
                    //echo '<meta http-equiv="refresh" content="0;URL=/friends/add">';
                }
                $this->view->generate('friends/friends_add_view.php', 'template_view.php','none_js.php', 'none_css.php');
            }else{
                echo '<meta http-equiv="refresh" content="0;URL=/auth/login">';
            }
        }

        function action_list()
        {
            if (isset($_SESSION['login'])){
                $err=$this->model->get_all_friends($_SESSION['login']);
                if ($err!=null){
                    ErrorHandler::addError($err);
                    ErrorHandler::displayErrors();
                    $this->model->close_connection();
                    return;
                }
                $err=$this->model->get_all_players($_SESSION['login']);
                if ($err!=null){
                    ErrorHandler::addError($err);
                    ErrorHandler::displayErrors();
                    $this->model->close_connection();
                    return;
                }
                $err=$this->model->get_all_subscribers($_SESSION['login']);
                if ($err!=null){
                    ErrorHandler::addError($err);
                    ErrorHandler::displayErrors();
                    $this->model->close_connection();
                    return;
                }
                $this->model->close_connection();
                $friends=$this->model->friends;
                $players=$this->model->players;
                $subscribers=$this->model->subscribers;
                $arr['friends']=$friends;
                $arr['players']=$players;
                $arr['subscribers']=$subscribers;
                //print_r($arr);
                //$this->view->generate('friends/friends_list_view.php', 'template_view.php','none_js.php', 'none_css.php', $arr);
                $this->view->generate('friends/friends_list_view.php', 'template_json_view.php','friends_list_js.php', 'none_css.php', $arr);
                //echo '<div>lolka123</div>';
            }else{
                echo '<div>lolka</div>';
                //echo '<meta http-equiv="refresh" content="0;URL=/auth/login">';
            }
        }
    }
?>