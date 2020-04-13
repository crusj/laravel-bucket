<?php
/**
 * author crusj
 * date   2019/10/24 2:10 下午
 */


namespace Crusj\Bucket\Command;


use App\Http\Services\ServiceFactory;
use Illuminate\Console\Command;

/**
 * 注册所有逻辑服务类
 * Class RegisterAllServices
 * @package Crusj\Bucket\Command
 */
class RegisterAllServices extends Command
{
    protected $signature = 'bucket:rsa';
    protected $description = 'Register all services to app/Http/Services/ServiceFactory';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $classNames = $this->getAllClassNames();
        $validNames = $this->filterRegisteredService($classNames);//有效类名
        $this->addMethodsToDocBatch($validNames);
    }

    private function getAllClassNames(): array
    {
        $path = app_path('Http/Services');
        $files = array();
        if ($head = opendir($path)) {
            while (($file = readdir($head)) !== false) {
                if ($file != ".." && $file != ".") {
                    $className = explode('.', $file);
                    if ($className[0] != 'ServiceFactory') {
                        $files[] = $className[0];
                    }
                }
            }
        }
        closedir($head);
        return $files;
    }

    /**
     * 过滤已经注册的service
     * @param array $classNames
     * @return array
     */
    private function filterRegisteredService(array $classNames): array
    {
        $alreadyClassNames = [];

        $ref = new \ReflectionClass(ServiceFactory::class);
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
     * 批量注册服务
     * @param array $classNames
     */
    private function addMethodsToDocBatch(array $classNames)
    {
        $ref = new \ReflectionClass(ServiceFactory::class);
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

        $commonServicePath = app_path('Http/Services/ServiceFactory.php');
        $content = file_get_contents($commonServicePath);
        $newContent = str_replace($docs, $newDoc, $content);

        file_put_contents($commonServicePath, $newContent);
        echo array_reduce($classNames,function ($carry,$item){
            return $carry .= app_path("Http/Services").'/'.$item."\n";
        },"以下类注册成功:\n");
    }

}
