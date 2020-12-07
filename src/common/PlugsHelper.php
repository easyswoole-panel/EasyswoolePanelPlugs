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