<?php
    /*
    * App Core Class
    * Creates URL & loads core controller
    * FORMAT - /controller/method/params
    */
    class Core {
        protected $currentController = 'Pages';
        protected $currentMethod = 'index';
        protected $params = [];

        public function __construct() {
            //print_r($this->getURL());
            $url = $this->getURL();

            //Look in controller for first element
            if(!empty($url)) {
                if(file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
                    //If exists, then set as current controller
                    $this->currentController = ucwords($url[0]);
                    //Unset 0 index of url
                    unset($url[0]);
                }
            }
            //Require the controller
            require_once '../app/controllers/' . $this->currentController . '.php';
            //Instantiate controller
            $this->currentController = new $this->currentController;

            //Check second part of url for method
            if(isset($url[1])) {
                if(method_exists($this->currentController, $url[1])) {
                    $this->currentMethod = $url[1];
                    //Unset 0 index of url
                    unset($url[1]);
                }
            }
            //echo $this->currentMethod;

            //Get params
            $this->params = $url ? array_values($url) : [];
            //Call a callback with array of params
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        }

        public function getURL() {
            if(isset($_GET['url'])) {
                $url = rtrim($_GET['url'], '/');
                $url = filter_var($url, FILTER_SANITIZE_URL);
                $url = explode('/', $url);
                return $url;
            }
        }
    }