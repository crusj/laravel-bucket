<?php
/**
 * author crusj
 * date   2020/4/24 2:19 下午
 */


namespace Crusj\Bucket\test\factory;


use Crusj\Bucket\ObjectFactory;

/**
 * Class FactorySingle
 * @method FactorySingleOne factorySingleOne(bool $refresh = true) static
 * @method \Crusj\Bucket\test\factory\sub\FactorySingleTwo subFactorySingleTwo(bool $refresh = true) static
 * @package Crusj\Bucket\test
 */
class FactorySingle extends ObjectFactory
{

}
