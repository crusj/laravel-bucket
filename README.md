
## 使用

* `composer require crusj/laravel-bucket`
* `php artisan vendor:publish --provider="Crusj\Bucket\BucketServiceProvider"`

## api

* app/Http目录下会生成apiException与Controllers/api/Common
* 新的api接口类继承Common,Common里是接口成功或失败返回的方法,目前存在success和fail,可以扩展
* 可在config/bucket.php配置返回的状态码

## factory

* 所有逻辑类放在app/services下，该目录下会生成ServiceFactory类
* 使用`php artisan bucket:rs className`注册类到ServiceFactory
* 使用ServiceFactory::className()将生成该逻辑类的实例
* `php artisan bucket:rsg`注册app/services下所有类到ServiceFactory
