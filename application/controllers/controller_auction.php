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
                if (isset($_POST['submit'])){
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
                    $this->view->generate_react('template_react_view.php', 'auction_js.php');
                    //$this->view->generate('friends/friends_add_view.php', 'template_view.php','none_js.php', 'none_css.php');
                } 
            }else{
                echo '<meta http-equiv="refresh" content="0;URL=/auth/login">';
            }
        }

        function action_add(){
            if (isset($_SESSION['login'])){
                if (isset($_POST['submit'])){
                    $card=new Card($_POST['card_id'], null, null, null, null, null, null, null);
                    $auction_item = new Auction_Item(null, $_SESSION['login'], null, $card, $_POST["quantity"], $_POST["start_price"], null, date('Y-m-d H:i:s'), $_POST["sell_date"]);
                    $err=$this->model->add_item($auction_item);
                    if ($err!=null){
                        ErrorHandler::addError($err);
                        ErrorHandler::displayErrors();
                        $this->model->close_connection();
                        return;
                    }
                    $this->model->close_connection();
                }
                }else{
                 echo '<meta http-equiv="refresh" content="0;URL=/auth/login">';
             }
        }

        function action_changePrice(){
            if (isset($_SESSION['login'])){
                if (isset($_POST['submit'])){
                    $err=$this->model->change_price($_POST['id'], $_POST['new_price'], $_SESSION['login']);
                    if ($err!=null){
                        ErrorHandler::addError($err);
                        ErrorHandler::displayErrors();
                        $this->model->close_connection();
                        return;
                    }
                    $this->model->close_connection();
                }
                }else{
                 echo '<meta http-equiv="refresh" content="0;URL=/auth/login">';
             }
        }
    }
?>