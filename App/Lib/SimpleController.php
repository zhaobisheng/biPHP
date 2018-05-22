<?php
namespace App\Lib;
use App\Lib\Request;
use App\Lib\ToJSONResult;
use App\Lib\Session;
class SimpleController{
    public $db;
    public $request;
    public $json;
    public $session;
    function __construct()
    {
        $this->db=$GLOBALS['db']; 
        $this->request=new Request();
        $this->json=new ToJSONResult();
        $this->session=new Session();
    }
}