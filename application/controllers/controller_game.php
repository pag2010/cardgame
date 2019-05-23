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
                echo "Добро пожаловать ".$_SESSION['login'];
                echo $this->model->get_unRead_all($_SESSION['login']);
                $this->view->generate('game/info_game_view.php', 'template_view.php', 'none_js.php', 'none_css.php', $this->model->unRead);
            }else{
                echo '<meta http-equiv="refresh" content="0;URL=/auth/login">';
            }
        }
        function action_collection()
        {
            
        }
    }
?>