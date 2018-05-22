<?php
spl_autoload_register(function($className)
{
    
    //echo "所有的包含文件工作都交给我！\r\n";
   
    $classFileName = "./{$className}.php";
     if(!file_exists($classFileName)){ $classFileName = BI_ROOT . "App/Model/".$className.".php";}
    //echo "我来包含！{$classFileName}\r\n";
    include "{$classFileName}";
}, true, true);

?>