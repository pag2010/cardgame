<?php
    session_start();
    require_once("error_handler.php");
    class Controller_Auction extends Controller
    {
        function __construct()
        {
            $this->model=new Model_Auction();
            //$this->storage=new Storage_Friends($_SESSION['login'], $this->model);
            $this->view=new View();
        }

	    function action_index(){
            if (isset($_SESSION['login'])){
               $err=$this->model->get_all_auction();
               if ($err!=null){
                ErrorHandler::addError($err);
                ErrorHandler::displayErrors();
                $this->model->close_connection();
                return;
               }
               $this->model->close_connection();
               $this->view->generatejson($this->model->auction_list); 
            }else{
                echo '<meta http-equiv="refresh" content="0;URL=/auth/login">';
            }
        }

        function action_add(){
            //if (isset($_SESSION['login'])){
                if (isset($_POST['submit'])){
                    //$err=$this->model->get_all_auction();
                    /*if ($err!=null){
                    ErrorHandler::addError($err);
                    ErrorHandler::displayErrors();
                    $this->model->close_connection();
                    return;
                    }*/
                    $auction_item = json_decode(file_get_contents('php://input'));
                    print_r($auction_item);
                    //$auction_item = new Auction_Item($_POST["id"], $row["seller"],$row["buyer"], $card, $row["quantity"], $row["start_price"],$row["sell_price"], $row["start_date"], $row["sell_date"]);
                // $this->model->close_connection();
                // $this->view->generatejson($this->model->auction_list); 
                }else{
                    $this->view->generate_react('template_react_view.php','friends_list_js.php');
                }
            /* }else{
                 echo '<meta http-equiv="refresh" content="0;URL=/auth/login">';
             } */
        }
    }
?>