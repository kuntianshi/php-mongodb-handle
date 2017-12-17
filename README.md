# php-mongodb
利用PHP操作Mongodb数据库的类
# 使用说明
## 1.请使用Mongodb版本的PHP扩展,使用前先安装PHP扩展
# 如何使用php-mongodb
## 1.在.env里配置uri和数据库
### 1)配置连接uri
MONGO_DB_URI=
### 2)配置数据库
MONGO_DB = 
## 2.在new数据库对象的时候带上uri和db
$db = new Manage($uri, $db);
# 功能
## 连接数据库
## 管理数据库 manage db
1)获取数据库示例信息
2)新建删除数据库
## 管理表 manage collection
1)对表进行增删改查
2)对collection进行索引重建
3)获取表信息
