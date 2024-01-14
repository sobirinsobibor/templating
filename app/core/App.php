<?php

class App {
  
protected $controller = 'Home'; //default controller
protected $method ='index'; //default method
protected $params = [];  //parameter biasa dipakai untuk mengirim ID

    public function __construct() {
        $url = $this->parseURL();
       
        //controller
        if($url == null){
            $url = [$this->controller];
        }
        if(file_exists('../app/controllers/' . ucfirst($url[0]) . 'Controller.php') ){
            $this->controller = $url[0];
            unset($url[0]);
        }else{
            $this->controller = 'NotFound';
        }

        require_once '../app/controllers/' . $this->controller  . 'Controller.php';
        $this->controller = new $this->controller;

        //method
        if(isset($url[1]) ){
            if(method_exists($this->controller, $url[1]) ){
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // params
        if(!empty($url)){
           $this->params = array_values($url);
        }

        //jalankan controller dan mehod dan kirimkan params  jika ada 
        call_user_func_array([$this->controller, $this->method], $this->params);

    }


    public function parseURL(){ //fungsi untuk memecah url menjadi controler, method, dan function
        if(isset($_GET['url']) ){
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }



}