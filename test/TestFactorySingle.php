<?php
/**
 * author crusj
 * date   2020/4/24 2:18 下午
 */


namespace Crusj\Bucket\test;


use Crusj\Bucket\test\factory\FactorySingle;
use PHPUnit\Framework\TestCase;

/**
 * 测试是否生成单例
 * Class TestFactorySingle
 * @package Crusj\Bucket\test
 */
class TestFactorySingle extends TestCase
{
    private $newName;
    private $originName;

    protected function setUp(): void
    {
        $this->newName = "李四";
        $this->originName = "张三";
    }

    //当前目录
    public function testCurrentDir()
    {
        $singleOne = FactorySingle::factorySingleOne(false);
        $singleOne->name = $this->newName;

        $singleTwo = FactorySingle::factorySingleOne(false);
        $this->assertEquals($singleTwo->name, $this->newName);

        //重新生成实例
        $singleThree = FactorySingle::factorySingleOne(true);
        $this->assertEquals($singleThree->name, $this->originName);
    }

    //二级目录
    public function testSubDir()
    {
        $singleOne = FactorySingle::subFactorySingleTwo(false);
        $singleOne->name = $this->newName;

        $singleTwo = FactorySingle::subFactorySingleTwo(false);
        $this->assertEquals($singleTwo->name, $this->newName);

        //重新生成实例
        $singleThree = FactorySingle::subFactorySingleTwo(true);
        $this->assertEquals($singleThree->name, $this->originName);
    }
}

