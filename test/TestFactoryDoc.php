<?php
/**
 * author crusj
 * date   2020/4/24 5:10 下午
 */


namespace Crusj\Bucket\test;


use Crusj\Bucket\bucket\FactoryDoc;
use Crusj\Bucket\test\factory\FactorySingle;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\CodeCoverage\Report\PHP;

class TestFactoryDoc extends TestCase
{
    private static $fileDocOne = __DIR__ . DIRECTORY_SEPARATOR . 'factory' . DIRECTORY_SEPARATOR . "FactoryDocOne.php";
    private static $subFileDocOne = __DIR__ . DIRECTORY_SEPARATOR . 'factory' . DIRECTORY_SEPARATOR . "sub" . DIRECTORY_SEPARATOR . "FactoryDocOne.php";

    private static $originFactory;

    public static function setUpBeforeClass(): void
    {
        self::$originFactory = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "factory" . DIRECTORY_SEPARATOR . "FactorySingle.php");
        $content = <<<EOT
<?php
namespace Crusj\\Bucket\\test\\factory;
class FactoryDocOne {
    public \$name="张三";
}
EOT;
        file_put_contents(self::$fileDocOne, $content);
        $content = <<<EOT
<?php
namespace Crusj\\Bucket\\test\\factory\\sub;
class FactoryDocOne {
    public \$name="张三";
}
EOT;
        file_put_contents(self::$subFileDocOne, $content);

    }

    public function testAddNewNames()
    {
        $factoryDoc = new FactoryDoc();
        $factoryDoc->parseDoc(new FactorySingle());
        $factoryDoc->addNewNames([
            "factoryDocOne",
            "factoryDocError",
            "sub_factoryDocOne"
        ]);
        $this->assertNotEmpty($factoryDoc->valid);
        $this->assertEquals([
            '\Crusj\Bucket\test\factory\FactoryDocOne' => 'FactoryDocOne',
            '\Crusj\Bucket\test\factory\sub\FactoryDocOne' => 'subFactoryDocOne'
        ], $factoryDoc->valid);
        $this->assertNotEmpty($factoryDoc->invalid);
        $this->assertEquals(array_keys($factoryDoc->invalid), [
            '\Crusj\Bucket\test\factory\FactoryDocError'
        ]);
        return $factoryDoc;
    }

    /**
     * @depends testAddNewNames
     * @param FactoryDoc $factoryDoc
     */
    public function testAddClassToDoc(FactoryDoc $factoryDoc)
    {
        $factoryDoc->addClassToDoc();
        $this->assertEquals($factoryDoc->success, [
            '\Crusj\Bucket\test\factory\FactoryDocOne',
            '\Crusj\Bucket\test\factory\sub\FactoryDocOne',
        ],"成功");
        $this->assertEquals(array_keys($factoryDoc->fail),[
            '\Crusj\Bucket\test\factory\FactoryDocError'
        ],"失败");
    }

    public static function tearDownAfterClass(): void
    {
        unlink(self::$fileDocOne);
        unlink(self::$subFileDocOne);
        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . "factory" . DIRECTORY_SEPARATOR . "FactorySingle.php", self::$originFactory);
    }
}
