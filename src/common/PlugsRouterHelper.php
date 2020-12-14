<?php
/**
 * 插件 路由 助手
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
use EasySwoole\Http\AbstractInterface\AbstractRouter;
class PlugsRouterHelper
{
    use Singleton;

    /**
     * 动态注入路由
     * @param $array
     * @param null $callable
     * @return bool
     */
    public function addGetRouter($array, $callable = null)
    {
        $router = PlugsContain::$router;
        $routeCollector = $router->getRouteCollector();

        // 兼容注入单条 字符
        if (!is_array($array)){
            $routeCollector->addRoute(["GET"], $array, $callable);
            return true;
        }

        foreach ($array as $key => $runner){
            $routeCollector->addRoute(["GET"], $key, function(Request $request, Response $response) use($runner){
                DispatcherPlugs::getInstance()->run($runner[0], $runner[1],$request, $response);
            });
        }
        return true;
    }

    /**
     * 动态注入路由 GET POST
     * @param string|array $array
     * @param null $callable 处理回调
     * @param AbstractRouter $router
     */
    public function addAnyRouter($array, $callable = null)
    {
        $router = PlugsContain::$router;
        $routeCollector = $router->getRouteCollector();
        // 兼容注入单条 字符
        if (!is_array($array)){
            return $routeCollector->addRoute(["GET", "POST"], $array, $callable);
        }

        foreach ($array as $key => $runner){
            $routeCollector->addRoute(["GET", "POST"], $key, function(Request $request, Response $response) use($runner){
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
            echo "{$plugsName} -> {$fileName} not exist \n";
            return false;
        }
        if (!is_dir($mirateFileDir)){
            File::createDirectory($mirateFileDir);
        }

        $res = File::copyFile($fullFilePath, $mirateFilePath);
        return $res;
    }

    /**
     * 迁移包内所有view文件到前端public中
     * @param $file
     */
    public function mirateViewAll($plugsName)
    {
        $fullFileDir   = PlugsAuthService::plugsPath($plugsName)."src/view/";
        $mirateFileDir = EASYSWOOLE_ROOT."/public/nepadmin/views/{$plugsName}";

        $fileList = File::scanDirectory($fullFileDir);
        foreach ( $fileList['files'] as $file ){
            if (!is_dir($fullFileDir)) File::createDirectory($fullFileDir);
            $realyFileName = str_replace($fullFileDir, "", $file);
            File::copyFile($file, $mirateFileDir."/".$realyFileName);
        }
    }

}