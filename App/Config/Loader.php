<?php
include_once(BI_ROOT . 'App/Lib/' . 'Autoload.php');
require_once('Config.php');
use App\Lib\DB\Bi_Mysqli; 

if($dbopen)
{
$db = new Bi_Mysqli($dbhost, $dbuser, $dbpw, $dbname, $dbport,$dbcharset);
}else{
    $db =null;
}
?>