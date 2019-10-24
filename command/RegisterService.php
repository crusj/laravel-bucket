<?php
/**
 * author crusj
 * date   2019/10/21 3:36 下午
 */

namespace Crusj\Bucket\Command;

use Illuminate\Console\Command;
use App\Services\ServiceFactory;

/**
 * 注册逻辑服务
 * Class RegisterService
 * @package Crusj\Bucket\Command
 */
class RegisterService extends Command
{
    protected $signature = 'bucket:rs {name}';
    protected $description = 'register service to Services/Common';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->argument('name');//服务名称
        if (empty($name)) {
            echo sprintf('name不能为空');
        }
        self::addMethodToDoc($name);
    }

    /**
     * 将类注册至Factory
     * @param $className
     * @throws \ReflectionException
     */
    public static function addMethodToDoc($className)
    {
        $ref = new \ReflectionClass(ServiceFactory::class);
        $docs = $ref->getDocComment();
        $eachLine = explode(PHP_EOL, $docs);
        $className = ucfirst($className);
        if (!is_file(app_path('Services/' . $className . '.php'))) {
            echo sprintf("类文件%s不存在\n", app_path('Services/' . $className . '.php'));
            return;
        }
        $method = lcfirst($className);
        $method = sprintf(" * @method %s", "$className $method(\$refresh = true) static");
        $index = 3;
        foreach ($eachLine as $key => $item) {
            if ($item == $method) {
                echo sprintf("类App\Services\%s已经注册\n", $className);
                return;
            }
            if (strpos($item, '@method') !== false) {
                $index = $key + 1;
            }
        }
        array_splice($eachLine, $index, 0, $method);
        $newDoc = join(PHP_EOL, $eachLine);
        $commonServicePath = app_path('Services/serviceFactory.php');
        $content = file_get_contents($commonServicePath);
        $newContent = str_replace($docs, $newDoc, $content);
        file_put_contents($commonServicePath, $newContent);
        echo sprintf("已将App\Services\%s注册至App\Services\ServiceFactory\n", $className);
    }
}
