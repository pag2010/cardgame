<?php
    session_start();
    require_once("error_handler.php");
    class Controller_Admin extends Controller
    {
        function __construct()
        {
            $this->model=new Model_Admin();
            //$this->storage=new Storage_Chat($_SESSION['login'], $this->model);
            $this->view=new View();
        }

	    function action_index()
	    {
            echo "lalala";
        }
        function action_cardcreate()
        {
            if ($_SESSION['login']=='admin'){
                if(isset($_POST['submit'])){
                    $err=$this->model->set_data($_POST);
                    $this->model->close_connection();
                    if ($err!=null){
                        ErrorHandler::addError($err);
                        ErrorHandler::displayErrors();
                        return;
                    }
                    //echo '<meta http-equiv="refresh" content="0;URL=/admin/cardcreate">';
                }
                $titles=$this->model->get_all_rarity();
                $kinds=$this->model->get_all_kind();
                $arr['rarity']=$titles;
                $arr['kind']=$kinds;
                $this->model->close_connection();
                $this->view->generate('admin/card_create_view.php', 'template_view.php','none_js.php', 'none_css.php', $arr);
            }else{
                ErrorHandler::addError("Доступ закрыт!");
                ErrorHandler::displayErrors();
                return;
            }
        }
    }
?>