﻿# biPHP
biPHP框架开发进度:

1.基础MVC(完成)

2.自制渲染模版引擎(完成)

3.自动加载(完成)

4.路由(完成)

5.请求(完成)

6.Mysql数据库连接(完成)

7.Session缓存封装(完成)

8.阿里云短信服务类库(完成)

9.sql注入过滤防护(完成)

## 说明
所有自带库类都可以通过use 命名空间引入

控制器命名约束 类名xxxxController 动作名xxxAction

eg:
```
class xxxxController
{
	public function xxxAction()   
	{  
		//function code.....        
	}  
}
```


## 路由格式

- 支持以下路由请求格式

```
http://xxxx.com/index.php?controller&action&id=1&user=bison


http://xxxx.com/index.php/controller/action/id=1&user=bison
```

## 使用Mysql

需要到 App\Config下的Config.php 设置数据库连接信息，一定要把 **$dbopen** 改成  **true**

```
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


```
