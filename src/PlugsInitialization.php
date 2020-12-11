<?php

namespace Siam\Plugs;


use EasySwoole\Component\TableManager;
use EasySwoole\EasySwoole\ServerManager;
use Siam\Plugs\common\PlugsContain;
use Siam\Plugs\common\PlugsHelper;
use Siam\Plugs\controller\Plugs;
use EasySwoole\Http\AbstractInterface\AbstractRouter;
use Siam\Plugs\service\PlugsAuthService;


class PlugsInitialization
{
    /**
     * 初始化 提供插件管理的几个api
     * @api /api/plugs/get_list
     * @api /api/plugs/install
     * @api /api/plugs/update
     * @api /api/plugs/remove
     */
    public static function initPlugsRouter(AbstractRouter $router)
    {
        PlugsHelper::getInstance()->addAnyRouter([
            '/api/plugs/get_list' => [new Plugs, 'get_list'],
            '/api/plugs/install'  => [new Plugs, 'install'],
            '/api/plugs/update'   => [new Plugs, 'update'],
        ]);

    }

    /**
     * 初始化插件系统  主要是处理其他插件
     */
    public static function initPlugsSystem()
    {
        // run only once
        $table = TableManager::getInstance()->get('plugs_status');
        if ($table->get('1')['init_ed'] == 1){
            return ;
        }
        $table->incr('1', 'init_ed');


        $plugsList = PlugsAuthService::getAllPlugs(true);
        foreach ($plugsList as $plug){
            // 将所有已经安装到插件到view 部署到前端（git 忽略）
            PlugsHelper::getInstance()->mirateViewAll($plug['plugs_name']);
            // 运行所有初始化文件
            $initializationFilePath = $plug['plugs_path']."/src/PlugsInitialization.php";
            if  ( is_file($initializationFilePath) ) {
                require_once $initializationFilePath;
            }
        }

    }
}