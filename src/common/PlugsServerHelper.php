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

    }

    /**
     * 重启easyswoole服务
     */
    public function restart()
    {
        $path = EASYSWOOLE_ROOT;
        exec("cd {$path} && ./restart_shell > {$path}/restart.log &");
    }


}