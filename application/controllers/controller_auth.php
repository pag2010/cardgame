<?php
    class Controller_Auth extends Controller
    {
        function __construct()
        {
            $this->model=new Model_Login();
            $this->view=new View();
        }
	    function action_index()
	    {
            $this->view->generate('auth/info_auth_view.php', 'template_view.php');
           
        }
        function action_registration()
        {
            $this->view->generate('auth/registration_view.php', 'template_view.php');
        }
        function action_login()
        {
            $this->view->generate('auth/login_view.php', 'template_view.php');
            if (isset($_POST['submit'])){
                $this->model->login=$_POST['login'];
                $err=$this->model->get_data();
                if ($err!=null){ 
                    echo 'Введён неверный логин или пароль';
                }else{
                   if ((strcmp(hash ('sha256', $_POST['password']), $this->model->password_hash))==0){
                       echo "email ";
                       echo $this->model->email;
                   }else{
                       //echo $this->model->password_hash;
                       echo hash("sha256", $_POST['password']);
                   }
                }
            }
        }
        function action_update()
        {
            $this->view->generate('auth/update_password.php', 'template_view.php');
        }

    }
?>