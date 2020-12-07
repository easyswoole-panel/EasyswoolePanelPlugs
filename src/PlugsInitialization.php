<?php

namespace Siam\Plugs;


use Siam\Plugs\common\PlugsHelper;
use Siam\Plugs\controller\Plugs;
use EasySwoole\Http\AbstractInterface\AbstractRouter;


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
        PlugsHelper::getInstance()->addGetRouter([
            '/api/plugs/get_list' => [new Plugs, 'get_list'],
            '/api/plugs/install'  => [new Plugs, 'install'],
            '/api/plugs/update'   => [new Plugs, 'update'],
        ], $router);
        PlugsHelper::getInstance()->mirateView("siam/plugs","list.html");
    }
}