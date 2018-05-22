<?php
/**
 * 自定义的模板引擎
 * 通过该类对象加载模板文件并解析，将解析后的结果输出
 */
namespace App\Lib;
class Template {
    public $template_dir = BI_ROOT . 'App/Views/' ; //定义模板文件存放的目录  
    public $compile_dir = BI_ROOT . 'App/Views/Cache/'; //定义通过模板引擎组合后文件存放目录
    public $source_dir = BI_ROOT . 'Public/'; //资源文件目录
    public $left_delimiter = '<{'; //在模板中嵌入动态数据变量的左定界符号
    public $right_delimiter = '}>'; //在模板中嵌入动态数据变量的右定界符号
    public $tpl_vars = array(); //内部使用的临时变量
    public $php_tag = array('if', 'elseif', 'include');
    public $left;
    public $right;

    function __construct() {
        /**
         * 将左右定界符号中，有影响正则的特殊符号转义  例如，<{ }>转义\<\{ \}\>
         */
        $this->left = preg_quote($this->left_delimiter, '/');
        $this->right = preg_quote($this->right_delimiter, '/');
        
    } 

    public function preg_callback($match) {
        $match_pattern = array(
            /**
             * 匹配模板中if标识符，例如 "<{ if $col == "sex" }> <{ /if }>"
             */
            '/' . $this->left . '\s*if\s*(.+?)\s*' . $this->right . '(.+?)' . $this->left . '\s*\/if\s*' . $this->right . '/is',
            /**
             * 匹配elseif标识符, 例如 "<{ elseif $col == "sex" }>"
             */
            '/' . $this->left . '\s*else\s*if\s*(.+?)\s*' . $this->right . '/is',
            /**
             * 匹配include标识符, 例如，'<{ include "header.html" }>'
             */
            '/' . $this->left . '\s*include\s+[\"\']?(.+?)[\"\']?\s*' . $this->right . '/i'
            );

        for($i = 0;$i < count($this->php_tag);$i++) {
            if (preg_match($match_pattern[$i], $match[0])) {
                if ($i == 0) {
                    return $this->stripvtags('<?php if(' . $match[1] . ') { ?> ' . $match[2] . ' <?php } ?>');
                } elseif ($i == 1) {
                    return '<?php } elseif(' . $match[1] . ') { ?>';
                } elseif ($i == 2) {
                    return file_get_contents($this->template_dir . "/$match[1]");
                } 
                return "error Template syntax !";
            } 
        } 
        return "Template error!";
    } 

    /**
     * 将PHP中分配的值会保存到成员属性$tpl_vars中，用于将模板中对应的变量进行替换
     * 
     * @param string $tpl_var 需要一个字符串参数作为关联数组下标，要和模板中的变量名对应
     * @param mixed $value 需要一个标量类型的值，用来分配给模板中变量的值
     */
    function assign($tpl_var, $value = null) {
        if ($tpl_var != '')
            $this->tpl_vars[$tpl_var] = $value;
    } 

    /**
     * 加载指定目录下的模板文件，并将替换后的内容生成组合文件存放到另一个指定目录下
     * 
     * @param string $fileName 提供模板文件的文件名
     */
    function display($fileName) {
        /**
         * 到指定的目录中寻找模板文件
         */
        $tplFile = $this->template_dir . '/' . $fileName;
        /**
         * 如果需要处理的模板文件不存在,则退出并报告错误
         */
        if (!file_exists($tplFile)) {
            die("模板文件{$tplFile}不存在！");
        } 
        /**
         * 获取组合的模板文件，该文件中的内容都是被替换过的
         */
        $comFileName = $this->compile_dir . "/BI_" . $fileName ;
        /**
         * 判断替换后的文件是否存在或是存在但有改动，都需要重新创建
         */
        if (!file_exists($comFileName) || filemtime($comFileName) < filemtime($tplFile)) { // time()-30
            /**
             * 调用内部替换模板方法
             */
            $repContent = $this->tpl_replace(file_get_contents($tplFile));
            /**
             * 保存由系统组合后的脚本文件
             */
            file_put_contents($comFileName, $repContent);
        } 
        // if(strpos($tplFile,"<{include")>0){$repContent = $this->tpl_replace(file_get_contents($comFileName)); file_put_contents($comFileName, $repContent);}
        /**
         * 包含处理后的模板文件输出给客户端
         */
        include($comFileName);
        if(!$GLOBALS['templateCache']){unlink($comFileName);}
        
    } 

    /**
     * 内部使用的私有方法，使用正则表达式将模板文件'<{ }>'中的语句替换为对应的值或PHP代码
     * 
     * @param string $content 提供从模板文件中读入的全部内容字符串
     * @return $repContent            返回替换后的字符串
     */
    private function tpl_replace($content) {
        /**
         * 匹配模板中各种标识符的正则表达式的模式数组
         */

        $pattern = array(
            /**
             * 匹配模板中变量 ,例如，"<{ $var }>"
             */
            '/' . $this->left . '\s*\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*' . $this->right . '/i',
            /**
             * 匹配else标识符, 例如 "<{ else }>"
             */
            '/' . $this->left . '\s*else\s*' . $this->right . '/is',
            /**
             * 用来匹配模板中的loop标识符，用来遍历数组中的值,  例如 "<{ loop $arrs $value }> <{ /loop}>"
             */
            '/' . $this->left . '\s*loop\s+\$(\S+)\s+\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*' . $this->right . '(.+?)' . $this->left . '\s*\/loop\s*' . $this->right . '/is',
            /**
             * 用来遍历数组中的键和值,例如 "<{ loop $arrs as  $key to $value }> <{ /loop}>"
             */
            '/' . $this->left . '\s*loop\s+\$(\S+)\s+as\s+\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*\=\>\s*\$(\S+)\s*' . $this->right . '(.+?)' . $this->left . '\s*\/loop\s*' . $this->right . '/is',
            '/' . $this->left . '\s*LoadJS\([\"\']?([0-9a-zA-Z_\/\.]*)[\"\']?\)\s*' . $this->right . '/is',
            '/' . $this->left . '\s*LoadCSS\([\"\']?([0-9a-zA-Z_\/\.]*)[\"\']?\)\s*' . $this->right . '/is'
            );
        $mix_pattern = array(
            /**
             * 匹配模板中if标识符，例如 "<{ if $col == "sex" }> <{ /if }>"
             */
            '/' . $this->left . '\s*if\s*(.+?)\s*' . $this->right . '(.+?)' . $this->left . '\s*\/if\s*' . $this->right . '/is',
            /**
             * 匹配elseif标识符, 例如 "<{ elseif $col == "sex" }>"
             */
            '/' . $this->left . '\s*else\s*if\s*(.+?)\s*' . $this->right . '/is',
            /**
             * 匹配include标识符, 例如，'<{ include "header.html" }>'
             */
            '/' . $this->left . '\s*include\s+[\"\']?(.+?)[\"\']?\s*' . $this->right . '/i'
            );
        /**
         * 替换从模板中使用正则表达式匹配到的字符串数组
         */
        $replacement = array(
            /**
             * 替换模板中的变量 <?php echo $this->tpl_vars["var"];
             */
            '<?php echo $this->tpl_vars["${1}"]; ?>',
            /**
             * 替换else的字符串 <?php } else { ?>
             */
            '<?php } else { ?>',
            /**
             * 以下两条用来替换模板中的loop标识符为foreach格式
             */
            '<?php foreach($this->tpl_vars["${1}"] as $this->tpl_vars["${2}"]) { ?>${3}<?php } ?>',

            /**
             * '<?php print_r($this->tpl_vars["${1}"]);?>',
             */
            '<?php foreach($this->tpl_vars["${1}"] as $this->tpl_vars["${2}"] => $this->tpl_vars["${3}"]) { ?>${4}<?php } ?>',
            '<script src="' . $this->curPageURL() . '${1}" language="javascript"></script>',
            '<link href="' . $this->curPageURL() . '${1}" rel="stylesheet" type="text/css" />'
            );

        /**
         * 使用正则替换函数处理
         */

        $repContent = preg_replace($pattern, $replacement, $content);
        $repContent = preg_replace_callback($mix_pattern, array($this, "preg_callback"), $repContent);
        /**
         * /* 如果还有要替换的标识,递归调用自己再次替换
         */
        if (preg_match('/' . $this->left . '([^(' . $this->right . ')]{1,})' . $this->right . '/', $repContent)) {
            $repContent = $this->tpl_replace($repContent);
        } 
        /**
         * 返回替换后的字符串
         */
        return $repContent;
    } 
    /**
     * 内部使用的私有方法，用来将条件语句中使用的变量替换为对应的值
     * 
     * @param string $expr 提供模板中条件语句的开始标记
     * @param string $statement 提供模板中条件语句的结束标记
     * @return strin 将处理后的条件语句相连后返回
     */
    private function stripvtags($expr) {
        /**
         * 匹配变量的正则
         */
        $var_pattern = '/\s*\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*/is';
        /**
         * 将变量替换为值
         */
        $expr = preg_replace($var_pattern, '$this->tpl_vars["${1}"]', $expr);
        /**
         * 将处理后的条件语句相连后返回
         */
        return $expr;
    } 

    public function curPageURL() {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"])) {
            if ($_SERVER["HTTPS"] == "on") {
                $pageURL .= "s";
            } 
        } 
        $pageURL .= "://";

        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . "/Public/";
        } 
        return $pageURL;
    } 
} 

?>