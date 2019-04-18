<?php
namespace App\Lib;
use App\Lib\Template;
use App\Lib\Request;
use App\Lib\Session;
class Controller{
    public $db;
    public $tpl;
    public $request;
    public $viewFile;
    public $session;
    public $json;
    function __construct()
    {
        $this->db=$GLOBALS['db']; 
        $this->tpl= new Template();
        $this->request=new Request();
        $this->viewFile=$GLOBALS['Controller'];
        $this->bookid=$GLOBALS['BookID'];
        $this->session=new Session();
        $this->json=new ToJSONResult();
    }
    public function display()
    {
        $this->tpl->display($this->viewFile.".html");
    }
    public function assign($key,$value)
    {
        $this->tpl->assign($key,$value);
    }
    public function setDisplay($view)
    {
         $this->viewFile=$view;
    }
    public function setDir($dir)
    {
         $this->tpl->setUserDir($dir);
    }
}