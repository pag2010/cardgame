<?php
    class View
    {
        //public $template_view; // здесь можно указать общий вид по умолчанию.
        
        function generate($content_view, $template_view, $content_js='none_js.php', $content_css='none_css.php', $data = null)
        {
            include 'application/views/'.$template_view;
        }
        function generatejson($data)
        {
            echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
        }

        function generate_react($template_view, $content_js='none_js.php', $data=null)
        {
            include 'application/views/'.$template_view;
        }
    }
?>