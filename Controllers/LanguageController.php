<?php
require_once './Models/LanguageModel.php';

class LanguageController extends BaseController{
 
    function index()
    {
        $this->views('language/list');
    }
}