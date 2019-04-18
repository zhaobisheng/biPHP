# biPHP
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
class xxxxController
{
	public function xxxAction()
	{
		//function code.....
	}
}

## 路由格式
支持以下路由请求格式
http://xxxx.com/index.php?controller&action&id=1&user=bison

http://xxxx.com/index.php/controller/action/id=1&user=bison