<?php
/**
 * author crusj
 * date   2019/10/21 3:36 下午
 */

namespace Crusj\Bucket\Command;

use Illuminate\Console\Command;

/**
 * 根据数据模型生成对应的逻辑服务类
 * Class GenerateServicesFromModels
 * @package Crusj\Bucket\Command
 */
class GenerateServicesFromModels extends Command
{
    protected $signature = 'bucket:gs';
    protected $description = 'Generate services from app/Http/Models/*';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $modelNames = $this->getModelNames();//获取所有数据模型类
        $validModelNames = $this->filterModelNames($modelNames);//过滤
        $validModelNames = array_merge($validModelNames,config('bucket.extra_service'));
        $validModelNames = array_unique($validModelNames);
        $this->generateServices($validModelNames);//生成服务逻辑类文件
    }

    /**
     * 获取模型名称
     * @return array
     */
    private function getModelNames(): array
    {
        $modelPath = app_path('Http/Models');
        $head = opendir($modelPath);

        $modelNames = [];
        while (($file = readdir($head)) !== false) {
            if ($file !== '.' && $file !== '..') {
                $names = explode('.', $file);
                $modelNames[] = $names[0];
            }
        }
        closedir($head);
        return $modelNames;
    }

    /**
     * 过滤无效数据模型类
     * @param array $modelNames
     * @return array
     */
    private function filterModelNames(array $modelNames): array
    {
        $filterModelNames = [];

        $except = ['ModelFactory'];
        if (!empty(config('bucket.parent_model'))) {
            $except[] = config('bucket.parent_model');
        }
        foreach ($modelNames as $item) {
            if (!in_array($item, $except)) {
                $filterModelNames[] = $item;
            }
        }
        return $filterModelNames;
    }

    /**
     * 生成逻辑服务类
     * @param array $modelNames
     */
    private function generateServices(array $modelNames)
    {
        $dir = app_path('Http/Services');
        $success = [];
        $fail = [];
        foreach ($modelNames as $item) {
            $fileName = $dir . '/' . $item . '.php';
            if (is_file($fileName)) {
                $fail[] = $fileName;
            } else {
                $success[] = $fileName;
                file_put_contents($fileName, $this->generateContentByModelName($item));
            }
        }
        $failMsg = array_reduce($fail, function ($carry, $item) use ($dir) {
            return $carry .= $item . "\n";
        }, "以下服务类由于已经存在生成失败:\n");
        $successMsg = array_reduce($success, function ($carry, $item) use($dir){
            return $carry .= $item . "\n";
        }, "以下服务类生成成功:\n");
        echo $successMsg;
        if (!empty($fail)) {
            echo $failMsg;
        }
    }

    private function generateContentByModelName(string $name): string
    {
        $parentService = config('bucket.parent_service');
        if (!empty($parentService) && $name != $parentService) {
            $class = <<<EOT
class {$name} extends {$parentService}
{
}
EOT;
        } else {
            $class = <<<EOT
class {$name}
{
}
EOT;

        }
        $date = 'Y/m/d h:i';
        $content = <<<EOT
<?php
/**
 * author crusj
 * date   {$date} 
 */
 
namespace App\Http\Services;

$class

EOT;

        return $content;
    }
}
