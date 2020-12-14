<?php
/**
 * 插件 数据表助手
 */

namespace Siam\Plugs\common;


use EasySwoole\Component\Singleton;
use EasySwoole\DDL\DDLBuilder;
use EasySwoole\DDL\Blueprint\Table;
use EasySwoole\EasySwoole\Config;
use EasySwoole\Mysqli\QueryBuilder;
use EasySwoole\ORM\DbManager;

class PlugsTableHelper
{
    use Singleton;

    /**
     * Author:chrisQx
     * @param string $table 表名(不带前缀)
     * @param callable $callable DDL回调
     * @return bool
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    function create(string $table, callable $callable): bool
    {
        //检查前缀
        $prefix     = Config::getInstance()->getConf('MYSQL.prefix');
        $tableName  = "{$prefix}{$table}";
        //检查表是否存在
        $TableSql   = "SHOW TABLES LIKE '{$prefix}{$table}'";
        $queryBuild = new QueryBuilder();
        $queryBuild->raw($TableSql);
        $checkTable = DbManager::getInstance()->query($queryBuild, true, 'default')->getResult();
        if($checkTable){
            throw new \Exception("表{$tableName}已存在");
        }

        //执行sql
        $sql = DDLBuilder::table($tableName, function (Table $table) use ($callable){
            $callable($table);
        });
        $queryBuild->raw($sql);
        $create = DbManager::getInstance()->query($queryBuild, true, 'default')->getResult();//bool
        if(!$create){
            throw new \Exception("表{$tableName}创建失败");
        }
        return $create;
    }

    // TODO 删除表
    function drop($tableName)
    {

    }

}