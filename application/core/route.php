<?php
    class Route
    {
        static function start()
        {
            // контроллер и действие по умолчанию
            $controller_name = 'Main';
            $action_name = 'index';
            
            $routes = explode('/', $_SERVER['REQUEST_URI']);
            // получаем имя контроллера
            if ( !empty($routes[1]) )
            {	
                $g=(strpos($routes[1], '?'));
                if ($g==false){
                    $controller_name = $routes[1];
                }else{
                    $controller_name=substr($routes[1],0 , $g);
                }
            }
            
            // получаем имя экшена
            if ( !empty($routes[2]) )
            {
                $action_name = $routes[2];
            }
    
            // добавляем префиксы
            $model_name = 'Model_'.$controller_name;
            $controller_name = 'Controller_'.$controller_name;
            $action_name = 'action_'.$action_name;
    
            // подцепляем файл с классом модели (файла модели может и не быть)
    
            $model_file = strtolower($model_name).'.php';
            //$model_path = "/app/application/models/".$model_file;
            $model_path = "application/models/".$model_file;
            //echo "file exists ".$model_path." ".(file_exists($model_path) ?'true':'false');
            if(file_exists($model_path))
            {
                //include "/app/application/models/".$model_file;
                include "application/models/".$model_file;
            }
    
            // подцепляем файл с классом контроллера
            $controller_file = strtolower($controller_name).'.php';
            //$controller_path = "/app/application/controllers/".$controller_file;
            $controller_path = "application/controllers/".$controller_file;
            if(file_exists($controller_path))
            {
                //include "/app/application/controllers/".$controller_file;
                include "application/controllers/".$controller_file;
            }
            else
            {
                /*
                правильно было бы кинуть здесь исключение,
                но для упрощения сразу сделаем редирект на страницу 404
                */
                Route::ErrorPage404();
            }
            
            // создаем контроллер
            $controller = new $controller_name;
            $action = $action_name;
            
            if(method_exists($controller, $action))
            {
                // вызываем действие контроллера
                $controller->$action();
            }
            else
            {
                // здесь также разумнее было бы кинуть исключение
                Route::ErrorPage404();
            }
        
        }
        
        static function ErrorPage404()
        {
            http_response_code(404);
            //include('/app/application/views/404_view.php'); // provide your own HTML for the error page
            include('application/views/404_view.php');
            die();
            /*$host = 'https://'.$_SERVER['HTTP_HOST'].'/';
            header('HTTP/1.1 404 Not Found');
            header("Status: 404 Not Found");
            header('Location:'.$host.'404');*/
        }
    }
?>