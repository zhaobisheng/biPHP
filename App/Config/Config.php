<?php
   	$dbopen=false;               //是否开启数据库功能,开启时改为为true
	$dbhost = '127.0.0.1';	    // 数据库服务器,win7以后使用127.0.0.1部分系统使用localhost会出现1-2秒的延时,Uinx/Linux随意
	$dbport = '3306';           //数据库端口
	$dbuser = 'bison';			// 数据库用户名
	$dbpw = 'bison';		// 数据库密码
	$dbname = 'mysql';	// 数据库名
	$pconnect = 0;				// 数据库持久连接 0=关闭, 1=打开
	$dbcharset = 'utf8';        //字符集编码
   	$templateCache=false; 
	$user_router=array();       //允许自定义用户二级目录，该目录需要放在 App\Controller 下，路由模式为:http://xxxx.com/自定义目录/controller/action/id=1&user=bison
?>
