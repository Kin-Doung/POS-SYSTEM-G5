<?php 
class BaseController{
    public function Views($view,$data = []){
        extract($data);
        ob_start();
        $content = ob_get_clean();
        require_once 'views/layout.php';
        require_once 'views/'. $view . '.php';
    }
    public function redirect($uri){
        header('Location:'.$uri);
        exit();
    }
}