
## 使用

* `composer require crusj/laravel-bucket`
* `php artisan vendor:publish --provider="Crusj\Bucket\BucketServiceProvider"`

## 增加文件
* app/Http/Models/ModelFactory.php 数据模型工厂类
* app/Http/Services/ServiceFactory.php 逻辑服务工厂类
* app/Http/apiException api异常类,可扩展
* app/Http/Controllers/Api api接口目录
* app/Config/bucket.php 配置文件

## api
* app/Http目录下会生成apiException与Controllers/Api/Common
* 新的api接口类继承Common,Common里是接口成功或失败返回的方法,目前存在success和fail,可以扩展
* 可在config/bucket.php配置返回的状态码
* `php artisan bucket:gi`根据app/Http/Models下的数据模型类生成对应的api类到app/Http/Controllers/Api

## factory

* 所有逻辑类放在app/services下，该目录下会生成ServiceFactory类
* 使用`php artisan bucket:rs className`注册app/Http/Services/类到ServiceFactory
* 使用ServiceFactory::className()将生成该逻辑类的实例
* `php artisan bucket:rsa`注册app/services下所有类到ServiceFactory
* `php artisan bucket:gs`根据app/Http/Models下的数据模型类生成对应的逻辑服务类到app/Http/Services
* `php artisan bucket:rm`注册app/Http/Models/类到ModelFactory
* `php artisan bucket:rma`注册app/Http/Models/所有类到ModelFactory

## 版本更新

## v1.2.3
* 修复生成Factory doc,windows平台无法正常解析文档问题

## v2.0.0

### 更新
* 抛出非SuccessResponse异常，响应字段error不再为传入数组，而是数组的第一个元素，默认值为未知错误
### 变更


