<?php
    class ErrorHandler{
        private static $messages=array();

        static function addError($err){
            self::$messages[]=$err;
        }

        static function displayErrors() {
            echo("<b>Возникли следующие ошибки:</b>\n<ul>\n");
      
            foreach(self::$messages as $msg){
                echo("<li>$msg</li>\n");
            }
            echo("</ul>\n");
        } 
    }
?>