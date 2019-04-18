<?php
spl_autoload_register(function($className)
{
    
    //echo "所有的包含文件工作都交给我！\r\n";
   if(!(strtolower(substr(PHP_OS, 0, 3)) == 'win')){
            $className=str_replace("\\","/",$className);
        }
    $classFileName = BI_ROOT."{$className}.php";
    //echo $classFileName."  :  ".$className;
     if(!file_exists($classFileName)){ $classFileName = BI_ROOT . "App/Model/".$className.".php";}
    //echo "我来包含！{$classFileName}\r\n";
    include "{$classFileName}";
}, true, true);

?>