<?php
namespace App\Lib;
class Pattern {
    public static $left_delimiter = '<{'; //在模板中嵌入动态数据变量的左定界符号
    public static $right_delimiter = '}>'; //在模板中嵌入动态数据变量的右定界符号
    public static $match_pattern = array(
        /**
         * 匹配模板中if标识符，例如 "<{ if $col == "sex" }> <{ /if }>"
         */
        '/' . $left . '\s*if\s*(.+?)\s*' . $right . '(.+?)' . $left . '\s*\/if\s*' . $right . '/is',
        /**
         * 匹配elseif标识符, 例如 "<{ elseif $col == "sex" }>"
         */
        '/' . $left . '\s*else\s*if\s*(.+?)\s*' . $right . '/is',
        /**
         * 匹配include标识符, 例如，'<{ include "header.html" }>'
         */
        '/' . $left . '\s*include\s+[\"\']?(.+?)[\"\']?\s*' . $right . '/i'
        );

    /**
     * 匹配模板中各种标识符的正则表达式的模式数组
     */

    public static $pattern = array(
        /**
         * 匹配模板中变量 ,例如，"<{ $var }>"
         */
        '/' . $left . '\s*\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*' . $right . '/i',
        /**
         * 匹配else标识符, 例如 "<{ else }>"
         */
        '/' . $left . '\s*else\s*' . $right . '/is',
        /**
         * 用来匹配模板中的loop标识符，用来遍历数组中的值,  例如 "<{ loop $arrs $value }> <{ /loop}>"
         */
        '/' . $left . '\s*loop\s+\$(\S+)\s+\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*' . $right . '(.+?)' . $left . '\s*\/loop\s*' . $right . '/is',
        /**
         * 用来遍历数组中的键和值,例如 "<{ loop $arrs $key => $value }> <{ /loop}>"
         */
        '/' . $left . '\s*loop\s+\$(\S+)\s+\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*=>\s*\$(\S+)\s*' . $right . '(.+?)' . $left . '\s*\/loop \s*' . $right . '/is',
        '/' . $left . '\s*LoadJS\([\"\']?([0-9a-zA-Z_\/\.]*)[\"\']?\)\s*' . $right . '/is',
        '/' . $left . '\s*LoadCSS\([\"\']?([0-9a-zA-Z_\/\.]*)[\"\']?\)\s*' . $right . '/is'
        );
    public static $mix_pattern = array(
        /**
         * 匹配模板中if标识符，例如 "<{ if $col == "sex" }> <{ /if }>"
         */
        '/' . $left . '\s*if\s*(.+?)\s*' . $right . '(.+?)' . $left . '\s*\/if\s*' . $right . '/is',
        /**
         * 匹配elseif标识符, 例如 "<{ elseif $col == "sex" }>"
         */
        '/' . $left . '\s*else\s*if\s*(.+?)\s*' . $right . '/is',
        /**
         * 匹配include标识符, 例如，'<{ include "header.html" }>'
         */
        '/' . $left . '\s*include\s+[\"\']?(.+?)[\"\']?\s*' . $right . '/i'
        );
    /**
     * 替换从模板中使用正则表达式匹配到的字符串数组
     */
    public static $replacement = array(
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
} 

?>