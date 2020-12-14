<?php
/**
 * 插件 数据表助手
 */

namespace Siam\Plugs\common;


use EasySwoole\Component\Singleton;

class PlugsTableHelper
{
    use Singleton;

    // TODO 引用ddl组件  闭包执行完就执行mysql
    function create($callable)
    {

    }

    // TODO 删除表
    function drop($tableName)
    {

    }
}