<?php
/**
 * author crusj
 * date   2020/4/24 4:24 下午
 */


namespace Crusj\Bucket\bucket;


class GenerateFile
{
    public function check(){
        foreach ($classNames as $className) {
            $classNames = explode("_", $className);
            $className = ucfirst(array_pop($classNames));
            //多级目录判断
            if (count($classNames) > 0) {
                $classDir = $this->factoryDir . join(DIRECTORY_SEPARATOR, $classNames) . DIRECTORY_SEPARATOR;
                if (!mkdir($classDir, 0777, true)) {
                    throw new \Exception(sprintf("创建目录%s失败,请检查执行权限", $classDir));
                }
                $classFileName = $classDir . ucfirst($className);
            } else {
                $classFileName = $this->factoryDir . ucfirst($className);
            }
            //命名空间
            $classNamespace = "\\" . str_replace(DIRECTORY_SEPARATOR, "\\", $classFileName);
            $classFileName = $classFileName . '.php';
            if (!file_exists($classFileName)) {
                $this->invalid($classFileName, );
            }
        }
    }
}
