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
扩展会在query对象上新增filter方法
```php
//filter方法接收三个参数，都有默认值可根据实际场景传入

$query->filter($input = [], array $allowFields=[],$filter = nul);
```
参数说明

|  参数名称   | 默认值  |说明  |
|  ----  | ----  |----  |
| $input  | [] | 需要过滤的参数数组，默认为空数组会获取 Request::param('filter')|
| $allowFields  | [] |允许自动过滤的字段，默认为空数组，所有字段都允许自动过滤 |
| $filter  | null | 自定义的过滤器类，默认会使用 app\filters\命名空间下以模型名称开头Filter结尾的过滤器类，如果该类不存则会使用Leegode\ThinkFilter\BaseFilter|


使用示例
```php
//模型使用
User::filter()->select();
//db使用
Db::table('users')->filter()->select();
db('user')->filter()->select();
```

### 简单查询

####   /users?filter[name]=zhangSan&filter[id]=>,10&filter[nick]=%,lee&filter[sort]=-create_time
>比如我们需要过滤用户列表的数据，前端根据路由参数了如上过滤参数
> 
> 注意：过滤器默认只会接受前端传入的filter数组

* sort参数为排序字段，+为ASC，-为DESC，多个字段排序用逗号分隔，例：filter[sort]=-create_time
```php
$query->order('create_time','desc')
```

* where条件查询字段，格式为：filter[字段]=操作符,值，操作符也可不传，默认为=操作，例：filter[name]=zhangSan

```php
$query->where('name','zhangSan')
```
* 目前支持的操作符有=，>,>=,<,<=,%、%为模糊查询，操作符为%，例：filter[nick]=%,lee
```php
$query->where('name','like','%lee%')
```
### 字段白名单配置
> 在一些场景中后端不想暴露出所有字段给前端查询，可以传入字段百年孤独配置，
> filter方法的第二个参数为允许自动过滤的字段名称，默认为空的情况下所有字段都可以自动过滤，
> 如果需要指定允许自动过滤的字段，可以已数组的方式传入该参数，
> 如果传入了该参数，自动过滤只会过滤指定的字段

### 自定义过滤器
> 自定义过滤器可以使用filter第三个参数传入，默认情况下不用传入，会自动使用app\filters命名空间下以模型名称开头Filter结尾的类，
> 如果该类存在则会默认使用Leegode\ThinkFilter\BaseFilter
> 自定义过滤器类需要继承Leegode\ThinkFilter\BaseFilter

过滤器方法需要以数据库字段命名(小驼峰)例:user_name= userName
```php
public function userName($userName){
   $this->where('user_name',$userName)//可以自定义一些复杂的查询逻辑
}

```


