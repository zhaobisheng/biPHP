<?php
use App\Lib\Utils;

$Controller = "Index";
$Action = 'Index';
$BookID="1";
$getVars = array();
// 获取所有请求
$request = $_SERVER['QUERY_STRING'];
$request1 = $_SERVER['REQUEST_URI'];
$startPos = stripos($request1, ".php");
$mode = substr_count($request1, '/', $startPos);
$hasParam = false;
$request1 = $startPos > 0?substr($request1, $startPos + 5):substr($request1, 1);
$user_dir = '';
if (strpos($request1, '=') > 0) {
    $hasParam = true;
} 

if ($mode > 1) {
    $parsed = explode('/' , $request1);
    $val = implode(',', $parsed);
    if (!empty($val)) {
        // 用户请求的控制器
        $temp = array_shift($parsed);
        if (in_array($temp, $user_router, true)) {
            $user_dir = ucfirst($temp);
            $Controller = array_shift($parsed);
        } else {
            $Controller = ucfirst($temp);
        } 
        $Action = array_shift($parsed);
        if ($hasParam) {
            $params = array_shift($parsed);
            $firstFlag = stripos($params, "?");
            if ($firstFlag>0){
                $params=substr($params,$firstFlag+1);
            }
            $parseParams = explode('&' , $params);
            $getVars = getParam($parseParams);
        }  
    } 
} else {
    $parsed = explode('&' , $request);
    $val = implode(',', $parsed);
    if (!empty($val)) {
        // 用户请求的控制器
        $temp = array_shift($parsed);
        if (in_array($temp, $user_router, true)) {
            $user_dir = $temp;
            $Controller = array_shift($parsed);
        } else {
            $Controller = $temp;
        } 
        $Action = array_shift($parsed);
        if ($hasParam) {
            $getVars = getParam($parsed);
        }      
    } 
} 

// 构成控制器文件路径
$target = BI_ROOT . 'App/Controllers/' . $user_dir . '/' . $Controller . 'Controller.php';

// 引入目标文件
if (file_exists($target)) {
    include_once($target); 
    // 修改Controller变量，以符合命名规范（如$Controller="news"，我们的约定是首字母大写，控制器的话就在后面加上Controller,即NewsController）
    $class = ucfirst($Controller) . 'Controller'; 
    // 初始化对应的类
    if (class_exists($class)) {
        $controller = new $class;
    } else {
        // 类的命名正确吗？
        die('class does not exist!');
    } 
} else { // 没有这个Controller
    die('Controller does not exist!');
} 

// 修改Action变量，以符合命名规范（如$Action="index"，我们的约定是首字母大写，控制器的话就在后面加上Action,即IndexAction）
$Action = strpos($Action, '#') > 0?substr($Action, 0, strpos($Action, '#')):$Action;
$Action = ucfirst($Action) . 'Action'; 
// 执行Action
if (method_exists($controller, $Action)) {
    $controller->$Action($getVars);
} else {
    // echo $Action."</br>";
    //die('Function does not exist!');
    die ("404 not found page!");
} 

function getParam($params) {
    $val = implode(',', $params);
    if (empty($val))return null;
    $Vars = array();
    foreach ($params as $argument) {
        // 用"="分隔字符串，左边为变量，右边为值
        list($variable , $valueSRC) = preg_split("/\=/" , $argument); 
        // if (is_numeric($valueSRC)) {
        // $value = Utils::check_id($valueSRC) > 0 ? Utils::check_id($valueSRC) : exit('illegal!');
        // } else {
        $value = strlen(Utils::check_str($valueSRC)) > 0 ? Utils::check_str($valueSRC) : exit('illegal!'); 
        // }
        $Vars[$variable] = $value;
    } 
    return $Vars;
} 

?>  