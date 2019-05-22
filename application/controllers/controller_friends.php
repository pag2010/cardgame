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
                }else{
                    $this->view->generate('friends/friends_add_view.php', 'template_view.php','none_js.php', 'none_css.php');
                }
            }else{
                echo '<meta http-equiv="refresh" content="0;URL=/auth/login">';
            }
        }

        function action_del(){
            if (isset($_SESSION['login'])){
                if (isset($_POST['submit'])){
                    $err=$this->model->del_subscr($_SESSION['login'], $_POST['login']);
                    if ($err!=null){
                        ErrorHandler::addError($err);
                        ErrorHandler::displayErrors();
                        return;
                    }
                    echo "keked";
                    //echo '<meta http-equiv="refresh" content="0;URL=/friends/add">';
                }
            }else{
                echo '<meta http-equiv="refresh" content="0;URL=/auth/login">';
            }
        }

        function action_list()
        {
            if (isset($_SESSION['login'])){
                if (isset($_GET['page']) && isset($_GET['count'])){
                    if (isset($_GET['friends'])||isset($_GET['players'])||isset($_GET['subscribers']))
                    $arr=null;
                    if (isset($_GET['friends'])){
                        $err=$this->model->get_paged_friends($_SESSION['login'], $_GET['page'], $_GET['count']);
                        if ($err!=null){
                            ErrorHandler::addError($err);
                            ErrorHandler::displayErrors();
                            $this->model->close_connection();
                            return;
                        }
                        $arr['friends']=$this->model->friends;
                    }
                    if (isset($_GET['players'])){
                        $err=$this->model->get_paged_players($_SESSION['login'], $_GET['page'], $_GET['count']);
                        if ($err!=null){
                            ErrorHandler::addError($err);
                            ErrorHandler::displayErrors();
                            $this->model->close_connection();
                            return;
                        }
                        $arr['players']=$this->model->players;
                        //print_r($this->model->players);
                        //$this->model->close_connection();
                    }
                    if (isset($_GET['subscribers'])){
                        $err=$this->model->get_all_subscribers($_SESSION['login']);
                        if ($err!=null){
                            ErrorHandler::addError($err);
                            ErrorHandler::displayErrors();
                            $this->model->close_connection();
                            return;
                        }
                        $arr['subscribers']=$this->model->subscribers;
                        //$this->model->close_connection();
                    }
                    $this->model->close_connection();
                   /*$friends=$this->model->friends;
                    $players=$this->model->players;
                    $subscribers=$this->model->subscribers;
                    $arr['friends']=$friends;
                    $arr['players']=$players;
                    $arr['subscribers']=$subscribers;*/
                    $this->view->generatejson($arr); 
                    //$this->view->generate('friends/friends_list_view.php', 'template_json_view.php','friends_list_js.php', 'none_css.php', $arr); 
                }
                else{   
                    //print_r($arr);
                    //$this->view->generate('friends/friends_list_view.php', 'template_view.php','none_js.php', 'none_css.php', $arr);
                    $this->view->generate('friends/friends_list_view.php', 'template_react_view.php','friends_list_js.php', 'none_css.php', $arr);
                }
            }else{
                //echo '<div>lolka</div>';
                echo '<meta http-equiv="refresh" content="0;URL=/auth/login">';
            }
        }
    }
?>