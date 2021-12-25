# think-filter

基于thinkphp5.x和6.x的搜索过滤器，支持model和db调用，让你的搜索更加的优雅灵活。

## 安装
```
composer require leegode/think-filter
```
## 配置
* 5.x版本需要修改配置文件config/database.php下的配置项query为\Leegode\ThinkFilter\Query::class 
* 6.x版本自动安装无需配置

## 使用
user为你的表名称或者模型名称

模型使用
```php
User::filter()->select();
```
db或者Db::table使用
```php
db('user')->filter()->select();
```
### 简单查询
####  where条件查询 /users?filter[name]=zhangSan&filter[id]=>,10&filter[nick]=%,lee&filter[sort]=-create_time


```php
$filters = Request::param('filter');
$query = User::buildQuery()
  if($filters['name']){
        $query->where('name',$filters['name']);
    }
    if($filters['id']){
        $query->where('id','>',$filters['id']);
    }
    if($filters['nick']){
        $query->where('nick','like','%'.$filters['nick'].'%');
    }
    if($filters['sort']){
        $query->order('create_time','desc');
    }
$users = $query->select();
```
过滤参数传入后端默认接收为filter数组
* sort参数为排序字段，+为ASC，-为DESC，多个字段排序用逗号分隔，例：-create_time为
```php
$query->order('create_time','desc')
```
* 其它参数为数据库字段查询条件，操作符和值用英文逗号隔开，无操作符默认为=，例：>,10
* 目前可用操作符有=，>,>=,<,<=,%、%为模糊查询，例：name=%zhangSan为
```php
$query->where('name','like',%zhangSan为where%)
```
### 自定义过滤器
如果存在app\filters\模型名称Filter'类则会使用该类进行过滤，如果不存在则使用默认的过滤器,在定义过滤器时，需要注意继承Leegode\ThinkFilter\BaseFilter,
filter方法第二个参数也可以传入指定过滤器类,方便根据不同场景自定义过滤器

过滤器方法需要以数据库字段命名(小驼峰)例:user_name= userName
```php
public function userName($userName){
   $this->where('user_name',$userName)//可以自定义一些复杂的查询逻辑
}

```
场景过滤
filter第一个参数传入字符串时为场景值,会自动调用该场景下的过滤器下已scene拼上场景值的方法,例:editor=>sceneEditor
```php
//编辑场景下执行的过滤条件
public function sceneEditor($filters){
   $this->userName($filters['user_name'])//可以自定义一些复杂的查询逻辑和复用一些过滤方法
}
```

其它功能慢慢探索吧!

