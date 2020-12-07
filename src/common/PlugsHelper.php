<?php
/**
 * 插件基础支持
 * User: Siam
 * Date: 2020/12/2 0002
 * Time: 22:09
 */

namespace Siam\Plugs\common;


use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Swoole\EventHelper;
use EasySwoole\Utility\File;
use FastRoute\RouteCollector;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use Siam\Plugs\common\DispatcherPlugs;
use Siam\Plugs\controller\Plugs;
use Siam\Plugs\service\PlugsAuthService;
use Siam\Plugs\service\PlugsInstallService;

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

    /**
     * 迁移包内view文件到前端public中
     * @param $file
     */
    public function mirateView($plugsName, $fileName)
    {
        $fullFilePath   = PlugsAuthService::plugsPath($plugsName)."src/view/".$fileName;
        $mirateFilePath = EASYSWOOLE_ROOT."/public/nepadmin/views/{$plugsName}/$fileName";
        $mirateFileDir = EASYSWOOLE_ROOT."/public/nepadmin/views/{$plugsName}";

        if (!is_file($fullFilePath)){
            var_dump("file error");
            return false;
        }
        if (!is_dir($mirateFileDir)){
            File::createDirectory($mirateFileDir);
        }

        $res = File::copyFile($fullFilePath, $mirateFilePath);
        return $res;
    }
}