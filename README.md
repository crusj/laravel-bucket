
## 使用

* `composer require crusj/laravel-bucket`
* `php artisan vendor:publish --provider="Crusj\Bucket\BucketServiceProvider"`

## api

* app/Http目录下会生成apiException与Controllers/api/Common
* 新的api接口类继承Common,Common里是接口成功或失败返回的方法,目前存在success和fail,可以扩展
* 可在config/bucket.php配置返回的状态码

## factory

* 所有逻辑类放在app/services下，该目录下会生成ServiceFactory类
* 使用`php artisan bucket:rs className`注册app/Services/类到ServiceFactory
* 使用ServiceFactory::className()将生成该逻辑类的实例
* `php artisan bucket:rsa`注册app/services下所有类到ServiceFactory
* `php artisan bucket:gs`根据app/Models下的数据模型类生成对应的逻辑服务类到app/Services
* `php artisan bucket:rm`注册app/Models/类到ModelFactory
* `php artisan bucket:rma`注册app/Models/所有类到ModelFactory
* `php artisan bucket:gi`根据app/Models下的数据模型类生成对应的api类到app/Http/Controllers/Api
