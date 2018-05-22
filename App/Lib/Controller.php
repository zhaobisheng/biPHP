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
    function __construct()
    {
        $this->db=$GLOBALS['db']; 
        $this->tpl= new Template();
        $this->request=new Request();
        $this->viewFile=$GLOBALS['Controller'];
        $this->session=new Session();
    }
    public function display()
    {
        $this->tpl->display(ucfirst($this->viewFile).".html");
    }
    public function assign($key,$value)
    {
        $this->tpl->assign($key,$value);
    }
    public function setDisplay($view)
    {
         $this->viewFile=$view;
    }
}