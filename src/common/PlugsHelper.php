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
use EasySwoole\Http\AbstractInterface\AbstractRouter;
class PlugsHelper
{
    use Singleton;

    /**
     * 动态注入路由
     * @param $array
     * @param AbstractRouter $router
     */
    public function addGetRouter($array,AbstractRouter $router)
    {
        $routeCollector = $router->getRouteCollector();

        foreach ($array as $key => $runner){
            $routeCollector->get($key, function(Request $request, Response $response) use($runner){
                DispatcherPlugs::getInstance()->run($runner[0], $runner[1],$request, $response);
            });
        }
    }

    /**
     * 动态注入路由 GET POST
     * @param $array
     * @param AbstractRouter $router
     */
    public function addAnyRouter($array,AbstractRouter $router)
    {
        $routeCollector = $router->getRouteCollector();

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

    /**
     * TODO 是否包含某个插件
     * @param $plugsName
     */
    public function hasPlugs($plugsName)
    {

    }

    /**
     * 获取插件配置
     * @param $plugsName
     */
    public function getConfig($plugsName)
    {

    }

    /**
     * TODO 新增菜单
     * @param $menuName
     * @param $menuPath
     * @param $menuIcon
     */
    public function addMenu($menuName, $menuPath, $menuIcon)
    {
        
    }
}