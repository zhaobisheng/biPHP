<?php
use App\Lib\Controller;

class IndexController   extends Controller
{  
  
    function indexAction()  
    {  
        
        $data=array(array('name'=>'bison','school'=>'sise','sex'=>'女'),array('name'=>'bison1','school'=>'sise1','sex'=>'男'));        
        $this->assign('title',"测试首页");
        $this->assign('author',"TianGO_Team");
        $this->assign('users',$data);
        $this->display();
        
    }  
   
} 


