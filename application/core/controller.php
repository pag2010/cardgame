<?php
    class Controller {
	
        public $model;
        public $storage;
        public $view;
        
        function __construct()
        {
            $this->view = new View();
        }
        
        function action_index()
        {
        }
    }
?>