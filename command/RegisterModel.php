<?php
/**
 * author crusj
 * date   2019/10/21 3:36 下午
 */

namespace Crusj\Bucket\Command;

use App\Models\ModelFactory;
use Illuminate\Console\Command;

/**
 * 注册数据服务
 * Class RegisterModel
 * @package Crusj\Bucket\Command
 */
class RegisterModel extends Command
{
    protected $signature = 'bucket:rm {name}';
    protected $description = 'Register model to app/Models/ModelFactory';

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
    public function addMethodToDoc($className)
    {
        $ref = new \ReflectionObject(new ModelFactory());
        $docs = $ref->getDocComment();
        $eachLine = explode(PHP_EOL, $docs);
        $className = ucfirst($className);
        if (!is_file(app_path('Models/' . $className . '.php'))) {
            echo sprintf("类文件%s不存在\n", app_path('Models/' . $className . '.php'));
            return;
        }
        $method = lcfirst($className);
        $method = sprintf(" * @method %s", "$className $method(\$refresh = true) static");
        $index = 3;
        foreach ($eachLine as $key => $item) {
            if ($item == $method) {
                echo sprintf("类App\Models\%s已经注册\n", $className);
                return;
            }
            if (strpos($item, '@method') !== false) {
                $index = $key + 1;
            }
        }
        array_splice($eachLine, $index, 0, $method);
        $newDoc = join(PHP_EOL, $eachLine);
        $commonServicePath = app_path('Models/serviceFactory.php');
        $content = file_get_contents($commonServicePath);
        $newContent = str_replace($docs, $newDoc, $content);
        file_put_contents($commonServicePath, $newContent);
        echo sprintf("已将App\Models\%s注册至App\Models\ServiceFactory\n", $className);
    }
}
