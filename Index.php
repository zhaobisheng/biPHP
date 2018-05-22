<?php  
  
//应用的根目录就是index.php的父目录  
define("BI_ROOT", trim(__DIR__ . '/'));  

try{
   //$t1 = microtime(true);
    require_once(BI_ROOT . 'App/Config/' . 'Loader.php'); 
    require_once(BI_ROOT . 'App/' . 'Router.php'); 
    $t2 = microtime(true);
/*echo '耗时'.round($t2-$t1,4).'秒<br>';
echo 'Now memory_get_usage: ' . memory_get_usage() . '<br />';*/
} catch (Exception $e) {

    echo "Exception: ", $e->getMessage();
    echo '<pre>' . $e->getTraceAsString() . '</pre>';
     
} 