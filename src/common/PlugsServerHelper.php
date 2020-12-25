<?php
/**
 * 插件 server服务助手
 * User: Siam
 * Date: 2020/12/7 0007e
 * Time: 21:28
 */

namespace Siam\Plugs\common;


use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Command\DefaultCommand\Server;


class PlugsServerHelper extends Server
{

    use Singleton;

    /**
     * 停止easyswoole服务
     */
    public function stop()
    {
        $path = EASYSWOOLE_ROOT;
        exec("cd {$path} && php easyswoole server stop >> {$path}/Log/swoole.log &");
    }

    /**
     * 热重启easyswoole服务.，此处为热重启，可以用于更新worker start后才加载的文件（业务逻辑），主进程（如配置文件）不会被重启。
     */
    public function reload()
    {
        $path = EASYSWOOLE_ROOT;
        exec("cd {$path} && php easyswoole server reload >> {$path}/Log/swoole.log &");
    }

    /**
     * 重启easyswoole服务
     */
    public function restart()
    {
        $path = EASYSWOOLE_ROOT;
        exec("cd {$path} && ./restart_shell >> {$path}/Log/swoole.log &");
    }


}