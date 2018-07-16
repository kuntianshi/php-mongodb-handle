## php-mongodb
利用PHP操作Mongodb数据库的类
## 使用说明
请使用Mongodb版本的PHP扩展,使用前先安装PHP扩展
## 如何使用php-mongodb
使用步骤
#### step1 composer安装
composer require shikung/php-mongodb
#### step2配置数据库连接
配置数据库连接有2中方式
##### 1).在.env里配置uri和数据库
MONGO_DB_URI= //连接的url
MONGO_DB = //需要连接的数据库
##### 2).在new数据库对象的时候带上uri和db
$db = new Manage($uri, $db);

## for example
$db = new Manage();
###查询

$data = $db->find($collection,$condition, $field = [], $options = []);