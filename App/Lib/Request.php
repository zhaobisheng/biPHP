<?php
namespace App\Lib;
use App\Lib\Utils;
class Request {
    public static function get($key = '', $type = '') {
        return Request::getData($GLOBALS['getVars'], $key, $type);
    } 

    public static function getPost($key = '', $type = '') {
        return Request::getData($_POST, $key, $type);
    } 

    public static function getData($array = '', $key = '', $type = '') {
        if (empty($array)) return null; 
        // $val = implode(',', $array);
        // if(empty($val)) return "";
        $RequestData = array();
        foreach($array as $keyName => $gets) {
            $RequestData[$keyName] = strlen(Utils::check_str($gets)) > 0 ? Utils::check_str($gets) : exit('Illegal Parameter Data!');
        } 
        if ($key == '') {
            return $RequestData;
        } elseif ($type == '') {
            return $RequestData[$key];
        } elseif ($type = 'int') {
            if (is_numeric($RequestData[$key])) {
                return $RequestData[$key];
            } else {
                exit($key . ' Parameter not a numeric！');
            } 
        } 
    }
    public static function redirect($url)
    {
        header("Location: /".$url);
    }
} 
