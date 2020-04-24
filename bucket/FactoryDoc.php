<?php
/**
 * author crusj
 * date   2020/4/24 3:49 下午
 */


namespace Crusj\Bucket\bucket;

use Crusj\Bucket\ObjectFactory;

class FactoryDoc
{
    private $docs;
    private $originDoc;

    private $factoryNamespace = "";
    private $factoryDir = "";
    private $factoryName = "";

    //有效
    public $valid = [];
    //无效
    public $invalid = [];

    public $success = [];
    public $fail = [];

    /**
     * 解析DOC
     * @param ObjectFactory $factory
     */
    public function parseDoc(ObjectFactory $factory)
    {
        $ref = new \ReflectionObject($factory);
        $this->factoryNamespace = $ref->getNamespaceName();
        $this->factoryName = $ref->getShortName();
        $this->factoryDir = dirname($ref->getFileName()).DIRECTORY_SEPARATOR;

        $docs = $ref->getDocComment();
        $this->originDoc = $docs;
        $docs = str_replace(["\r\n", "\r", "\n"], "\n", $docs);
        $this->docs = explode("\n", $docs);
    }

    /**
     * 需要新生成的类
     * @param array $classNames
     */
    public function addNewNames(array $classNames)
    {
        foreach ($classNames as $className) {
            $classNames = explode("_", $className);
            $className = ucfirst(array_pop($classNames));
            //多级目录判断
            if (count($classNames) > 0) {
                $classDir = $this->factoryDir . join(DIRECTORY_SEPARATOR, $classNames) . DIRECTORY_SEPARATOR;
                $classFileName = $classDir . ucfirst($className) . ".php";
            } else {
                $classFileName = $this->factoryDir . ucfirst($className) . ".php";
            }
            //命名空间
            $tmp = join("\\", $classNames);
            if (empty($tmp)) {
                $classNamespace = '\\' . $this->factoryNamespace . '\\' . $className;
            } else {
                $classNamespace = '\\' . $this->factoryNamespace . '\\' . $tmp . '\\' . $className;
            }
            //文件是否存在
            if (!file_exists($classFileName)) {
                $this->invalid($classNamespace, sprintf("类文件%s不存在", $classFileName));
                $this->fail[$classNamespace] = sprintf("类文件%s不存在", $classFileName);
            } else {
                $this->valid($classNamespace, self::camelize(join("_", $classNames)) . $className);
            }
        }
    }

    //写入有效的类到DOC
    public function addClassToDoc()
    {
        foreach ($this->valid as $classNamespace => $method) {
            $method = lcfirst($method);
            $method = sprintf(" * @method %s", "$classNamespace $method(\$refresh = true) static");
            $index = 3;
            foreach ($this->docs as $key => $item) {
                if ($item == $method) {
                    $this->fail[$classNamespace] = sprintf("类%s已经注册\n", $classNamespace);
                    continue;
                }
                if (strpos($item, '@method') !== false) {
                    $index = $key + 1;
                }
            }
            array_splice($this->docs, $index, 0, $method);
            $this->success[] = $classNamespace;
        }
        $newDoc = join("\r\n", $this->docs);

        $content = file_get_contents($this->factoryDir . $this->factoryName.".php");
        $newContent = str_replace($this->originDoc, $newDoc, $content);
        file_put_contents($this->factoryDir . $this->factoryName.".php", $newContent);
    }

    //有效
    private function valid(string $classNamespace, string $methodName)
    {
        $this->valid[$classNamespace] = $methodName;

    }

    //无效
    private function invalid(string $className, string $reason)
    {
        $this->invalid[$className] = $reason;
    }

    //打印结果
    public function printResult()
    {
        $index = 1;
        foreach ($this->success as $key => $item) {
            printf("%d. %s 成功\n", $index, $item);
            $index++;
        }
        foreach ($this->fail as $key => $item) {
            printf("%d. %s 失败：%s\n", $index, $key, $item);
            $index++;
        }
    }

    //转驼峰
    static function camelize($uncamelized_words, $separator = '_'): string
    {
        $uncamelized_words = $separator . str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator);
    }


}
