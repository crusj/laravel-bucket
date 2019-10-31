<?php
/**
 * author crusj
 * date   2019/10/24 2:10 下午
 */


namespace Crusj\Bucket\Command;


use App\Models\ModelFactory;
use Illuminate\Console\Command;

/**
 * 注册所有数据服务类
 * Class RegisterAllModels
 * @package Crusj\Bucket\Command
 */
class RegisterAllModels extends Command
{
    protected $signature = 'bucket:rma';
    protected $description = 'Register all models to app/Models/ModelFactory';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $classNames = $this->getAllClassNames();
        $validNames = $this->filterRegisteredModel($classNames);//有效类名
        $this->addMethodsToDocBatch($validNames);
    }

    private function getAllClassNames(): array
    {
        $path = app_path('Models');
        $files = array();
        if ($head = opendir($path)) {
            while (($file = readdir($head)) !== false) {
                if ($file != ".." && $file != ".") {
                    $className = explode('.', $file);
                    if ($className[0] != 'ModelFactory') {
                        $files[] = $className[0];
                    }
                }
            }
        }
        closedir($head);
        return $files;
    }

    /**
     * 过滤已经注册的Model
     * @param array $classNames
     * @return array
     */
    private function filterRegisteredModel(array $classNames): array
    {
        $alreadyClassNames = [];

        $ref = new \ReflectionClass(ModelFactory::class);
        $docs = $ref->getDocComment();
        $eachLine = explode(PHP_EOL, $docs);
        $pattern = '/^.*@method ([a-zA-Z]+) .*$/';
        foreach ($eachLine as $key => $item) {
            if (preg_match($pattern, $item, $match) == 1) {
                $alreadyClassNames[] = $match[1];
            }
        }
        return array_diff($classNames, $alreadyClassNames);
    }

    /**
     * 批量注册数据
     * @param array $classNames
     */
    private function addMethodsToDocBatch(array $classNames)
    {
        $ref = new \ReflectionClass(ModelFactory::class);
        $docs = $ref->getDocComment();
        //找到最后一位
        $eachLine = explode(PHP_EOL, $docs);
        for ($i = 3; $i < count($eachLine); $i++) {
            if (strpos($eachLine[$i], '@method') !== false) {
                continue;
            } else {
                break;
            }
        }
        $insertPosition = $i;
        $methods = [];
        foreach ($classNames as $item){
            $item = ucfirst($item);
            $method = lcfirst($item);
            $methods[] = sprintf(" * @method %s", "$item $method(\$refresh = true) static");
        }

        array_splice($eachLine, $insertPosition, 0, $methods);
        $newDoc = join(PHP_EOL, $eachLine);

        $commonModelPath = app_path('Models/serviceFactory.php');
        $content = file_get_contents($commonModelPath);
        $newContent = str_replace($docs, $newDoc, $content);

        file_put_contents($commonModelPath, $newContent);
        echo array_reduce($classNames,function ($carry,$item){
            return $carry .= app_path("Models").'/'.$item."\n";
        },"以下类注册成功:\n");
    }

}
