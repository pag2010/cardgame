<?php
    class View
    {
        //public $template_view; // здесь можно указать общий вид по умолчанию.
        
        function generate($content_view, $template_view, $content_js='none_js.php', $data = null)
        {
            /*
            if(is_array($data)) {
                // преобразуем элементы массива в переменные
                extract($data);
            }
            */
            
            include 'application/views/'.$template_view;
        }
    }
?>