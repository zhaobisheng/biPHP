<?php
namespace App\Lib;
class Model{
    public $paramMap;
    public $condition;
    public $db;
    public $limit;
    public $order;
    public function test()
    {
        echo '11';
    }
    public function tableName()
    {
        $className=__CLASS__;
        $tables=substr($className,strripos($className,'\\')+1);
        echo $tables;
    }
    
    public function primaryKey()
    {
        echo 'primaryKey';
    }
    public function get()
    {
        echo 'this is get';
    }
    
    public function find()
    {
        echo 'this is find';
    }
    public function order($order)
    {
        $this->order="  order by ".$order;
        return $this;
    }
    public function limit($num)
    {
         $this->limit="  limit ".$num;
         return $this;
    }
    public function findOne()
    {
        $sql="select * from ".get_called_class()."  ".$this->condition." limit 1";
        return $GLOBALS['db']->fetch_one($sql);
    }
    public function findAll()
    {       
    
        $sql="select * from ".get_called_class()."  ".$this->condition .$this->order. $this->limit;
        return $GLOBALS['db']->fetch_array($sql);
    }
    public  function where($arr,$val='')
    {
        $this->condition="  where ";
        if(is_array($arr)){
            foreach($arr as $key =>$val)
                $this->condition.=$key." = '".$val."' AND ";
            $this->condition=substr($this->condition,0,strrpos($this->condition,'AND'));   
        }elseif($val==''){
            $this->condition.=$arr;
        }else{
             $this->condition.=$arr." = '".$val."'";
        }                 
        return $this;
    }
    public  function update($arr='')
    {
        $sql="update ".get_called_class()." set ";
        if(is_array($arr))
        {
            foreach($arr as $key =>$val)
            {
               $sql.=$key."='".$val."' , " ;
            }
            $sql=substr($sql,0,strrpos($sql,',')).$this->condition; 
        }else{
           $sql.=$arr.$this->condition;
        }
        return $GLOBALS['db']->bi_query($sql);
    }
    
    
     public function insert($arr='')
    {
        $sql="insert into  ".get_called_class()." ";
        $keyName=" ( ";
        $center=" values ";
        $valueName=" ( ";
        $keys=array();
        $vals=array();
        if(is_array($arr))
        {
            foreach($arr as $key =>$val)
            {
               $keyName.="`".$key."`,";
               $valueName.="'".$val."',";             
            }
            $keyName=substr($keyName,0,strrpos($keyName,',')).")"; 
            $valueName=substr($valueName,0,strrpos($valueName,',')).")"; 
        }
        $sql.=$keyName.$center.$valueName;
        return $GLOBALS['db']->bi_query($sql);
    }
    
    public function del()
    {
         $sql="delete  from ".get_called_class()."  ".$this->condition;
         return $GLOBALS['db']->bi_query($sql);
    }
}

?>