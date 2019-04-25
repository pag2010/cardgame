<?php
    session_start();
    ob_clean();
    require_once("error_handler.php");
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
                    //echo "На сервере получены учетные данные ";
                    echo '<meta http-equiv="refresh" content="0;URL=/auth/login">';
                    $err=$this->model->set_data();
                    if ($err!=null){
                        //echo "Произошла ошибка";
                        ErrorHandler::addError($err);
                        ErrorHandler::displayErrors();
                        return;
                    }
                }
            }
        }
        function action_login()
        {
            //$this->view->generate('auth/login_view.php', 'template_json_view.php', 'auth_login_js.php');
            //$this->view->generate('none_view.php', 'template_json_view.php', 'auth_login_js.php');
            if (isset($_POST['submit'])){
                $this->model->login=$_POST['login'];
                $err=$this->model->get_data();
                if ($err!=null){ 
                    var_dump(http_response_code(401));
                    //echo 'Введён неверный логин или пароль, пользователь не найден';
                    ErrorHandler::addError($err);
                    ErrorHandler::displayErrors();
                    
                }else{
                   if ((strcmp($_POST['password'], $this->model->password_hash))==0){
                       $_SESSION['logged_in']=true;
                       $_SESSION['login']=$_POST['login'];
                   }else{
                       var_dump(http_response_code(401));
                       ErrorHandler::addError('Введён неверный логин или ПАРОЛЬ');
                       ErrorHandler::displayErrors();
                   }
                }
            }
            else{
            $this->view->generate('auth/login_view.php', 'template_json_view.php', 'auth_login_js.php');
            }
        }
        function action_update()
        {
            $this->view->generate('auth/update_password.php', 'template_view.php');
        }

    }
?>