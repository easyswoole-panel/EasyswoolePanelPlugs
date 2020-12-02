<?php
/**
 * 插件基础支持
 * User: Siam
 * Date: 2020/12/2 0002
 * Time: 22:09
 */

namespace Siam\Plugs\common;


use EasySwoole\Component\Singleton;
use FastRoute\RouteCollector;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use Siam\Plugs\common\DispatcherPlugs;
use Siam\Plugs\controller\Plugs;

class PlugsHelper
{
    use Singleton;

    public function addGetRouter($array)
    {
        /** @var \FastRoute\RouteCollector $routeCollector */
        $routeCollector = \EasySwoole\EasySwoole\Http\Dispatcher::getInstance()->initRouter()->getRouteCollector();

        foreach ($array as $key => $runner){
            $routeCollector->get($key, function(Request $request, Response $response) use($runner){
                DispatcherPlugs::getInstance()->run($runner[0], $runner[1],$request, $response);
            });
        }
    }
}