<?php
    session_start();
    class Controller_Chat extends Controller
    {
        function __construct()
        {
            $this->model=new Model_Chat();
            $this->view=new View();
        }

	    function action_index()
	    {
            if (isset($_SESSION['login'])){
                $this->view->generate('chat/info_chat_view.php', 'template_view.php');
                $this->model->get_data();
                $arr=$this->model->chat_id;
                for($i=0; $i<count($arr); $i++){
                    echo "<input name='submit' type='submit' value='".$arr[$i]."' method=GET>";
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