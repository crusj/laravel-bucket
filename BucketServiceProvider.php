<?php
/**
 * author crusj
 * date   2019/10/20 3:51 下午
 */

namespace Crusj\Bucket;

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
        ]);
    }
}
