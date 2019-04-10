<?php
    session_start();
    class Controller_Game extends Controller
    {
        function __construct()
        {
            $this->model=new Model_Game();
            $this->view=new View();
        }

	    function action_index()
	    {
            if (isset($_SESSION['login'])){
                $this->view->generate('game/info_game_view.php', 'template_view.php');
                echo "Добро пожаловать ".$_SESSION['login'];
            }else{
                echo '<meta http-equiv="refresh" content="0;URL=/auth/login">';
            }
        }
        function action_collection(){
            if (isset($_SESSION['login'])){
                $this->model->get_collection($_SESSION['login']);
                $this->view->generate('game/collection_view.php', 'template_view.php', 'none_js.php', 'card_css.php', $this->model->collection_card);
            }
        }
    }
?>