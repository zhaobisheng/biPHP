<?php
namespace App\Lib;
class Utils {
    
    static $chars = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	
	static public function GetSalt() {
		$salt = "";		
		for ($num = 0; $num < 8; $num++) {
			$RandNum = rand(0, strlen(Utils::$chars) - 1);
			$salt .= Utils::$chars[$RandNum];
		}
		return $salt;
    }
    
    
    /**
     * 过滤sql与php文件操作的关键字
     * 
     * @param string $string 
     * @return string 
     */
    static public function filter_keyword($string) {
        $keyword = 'select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|and|union|order|or|into|load_file|outfile';
        $arr = explode('|', $keyword);
        $result = str_ireplace($arr, '', $string);
        return $result;
    } 

    /**
     * 检查输入的数字是否合法，合法返回对应id，否则返回false
     * 
     * @param integer $id 
     * @return mixed 
     */
    static public function check_id($id) {
        $result = false;
        if ($id !== '' && !is_null($id)) {
            $var = Utils::filter_keyword($id); // 过滤sql与php文件操作的关键字 
            if ($var !== '' && !is_null($var) && is_numeric($var)) {
                $result = intval($var);
            } 
        }elseif($var==0){
            return true;
        } 
        return $result;
    } 

    /**
     * 检查输入的字符是否合法，合法返回对应str，否则返回false
     * 
     * @param string $string 
     * @return mixed 
     */
    static public function check_str($string) {
        $result = false;
        $var = Utils::filter_keyword($string); // 过滤sql与php文件操作的关键字 
        if (!empty($var)) {
            if (!get_magic_quotes_gpc()) { // 判断magic_quotes_gpc是否为打开
                $var = addslashes($string); // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤 
            } 
            // $var = str_replace( "_", "\_", $var ); // 把 '_'过滤掉
            $var = str_replace("%", "\%", $var); // 把 '%'过滤掉 
            $var = nl2br($var); // 回车转换 
            $var = htmlspecialchars($var); // html标记转换 
            $result = strval($var);
        }elseif($var==0){
            return true;
        }
        return $result;
    }
    
    
    
} 

?>