<?php

namespace Siam\Plugs;


use EasySwoole\EasySwoole\ServerManager;
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
    public static function init(AbstractRouter $router)
    {
        PlugsHelper::getInstance()->addAnyRouter([
            '/api/plugs/get_list' => [new Plugs, 'get_list'],
            '/api/plugs/install'  => [new Plugs, 'install'],
            '/api/plugs/update'   => [new Plugs, 'update'],
        ], $router);

        // 将所有已经安装到插件到view 部署到前端（git 忽略）
//        if (ServerManager::getInstance()->getSwooleServer()->worker_id == 1){
            $plugsList = PlugsAuthService::getAllPlugs(true);
            foreach ($plugsList as $plug){
                PlugsHelper::getInstance()->mirateViewAll($plug['plugs_name']);
            }
//        }

    }
}