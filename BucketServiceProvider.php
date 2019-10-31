<?php
/**
 * author crusj
 * date   2019/10/20 3:51 下午
 */

namespace Crusj\Bucket;

use Crusj\Bucket\Command\GenerateApiControllersFromModels;
use Crusj\Bucket\Command\GenerateServicesFromModels;
use Crusj\Bucket\Command\RegisterAllModels;
use Crusj\Bucket\Command\RegisterModel;
use Crusj\Bucket\Command\RegisterService;
use Crusj\Bucket\Command\RegisterAllServices;
use Illuminate\Support\ServiceProvider;

class BucketServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/bucket.php' => config_path('bucket.php'),//配置文件
            __DIR__ . '/apiException'      => app_path('Http/apiException'),
            __DIR__ . '/api'               => app_path('Http/Controllers/api'),
            __DIR__ . '/service'           => app_path('Services'),//逻辑服务文件夹
            __DIR__ . '/model'           => app_path('Models'),//数据模型文件夹
        ]);
        //注册命令
        if($this->app->runningInConsole()){
            $this->commands([
                RegisterService::class,
                RegisterAllServices::class,
                GenerateServicesFromModels::class,
                RegisterModel::class,
                RegisterAllModels::class,
                GenerateApiControllersFromModels::class
            ]);
        }
    }
}
