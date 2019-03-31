<?php
    session_start();
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
            $this->view->generate('auth/registration_view.php', 'template_view.php', 'auth_registration_js.php');
            if (isset($_POST['submit'])){
                if (strcmp($_POST['password'], $_POST['password_confirm'])==0){
                    $this->model->login=$_POST['login'];
                    $this->model->password_hash=$_POST['password'];
                    $this->model->email=$_POST['email'];
                    echo "На сервере получены учетные данные";
                    $err=$this->model->set_data();
                    if ($err!=null){
                        echo "Произошла ошибка";
                        return;
                    }
                }
            }
        }
        function action_login()
        {
            $this->view->generate('auth/login_view.php', 'template_view.php', 'auth_login_js.php');
            if (isset($_POST['submit'])){
                $this->model->login=$_POST['login'];
                $err=$this->model->get_data();
                if ($err!=null){ 
                    echo 'Введён неверный логин или пароль, пользователь не найден';
                }else{
                   if ((strcmp($_POST['password'], $this->model->password_hash))==0){
                       $_SESSION['logged_in']=true;
                       $_SESSION['login']=$_POST['login'];
                        echo 'Авторизация пройдена успешно!';
                   }else{
                       echo 'Введён неверный логин или пароль '.$_POST['login'].' '.$_POST['password'];
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