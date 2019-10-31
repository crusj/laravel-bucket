<?php
/**
 * author crusj
 * date   2019/10/20 3:55 下午
 */
return [
    'success_code' => 2000,
    'fail_code'    => 4000,

    'parent_model' => '',//数据模型父类,注册模型类到数据模型工厂会进行过滤

    'extra_service'  => [],//额外的逻辑服务类,当通过model生成service时候额外生成的
    'parent_service' => '',//逻辑服务类父类,注册逻辑类到逻辑工厂会进行过滤,通过gs生成的类会继承此类


    'extra_api'  => [],//额外的api控制器类,当通过模型model生成controller时候额外生成的
    'parent_api' => 'Common',//api控制器父类,通过as生成的类会继承此类

];
