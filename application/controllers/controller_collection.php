<?php
    session_start();
    class Controller_Collection extends Controller
    {
        function __construct()
        {
            $this->model=new Model_Collection();
            $this->view=new View();
        }

	    function action_index()
	    {
            if (isset($_SESSION['login'])){
                if (isset($_GET['page'])&&isset($_GET['count'])){
                    $err= $this->model->get_all($_SESSION['login'], $_GET['page'], $_GET['count']);
                    if ($err!=null){
                        ErrorHandler::addError($err);
                        ErrorHandler::displayErrors();
                        $this->model->close_connection();
                        return;
                    }
                    $this->model->close_connection();
                    $this->view->generatejson($this->model->cards);
                }else{
                    $this->view->generate_react('template_react_view.php', 'collection_js.php');
                }
            }else{
                echo '<meta http-equiv="refresh" content="0;URL=/auth/login">';
            }
        }
        function action_collection()
        {
            
        }
    }
?>