<?php

namespace Siam\Plugs;


use Siam\Plugs\common\PlugsHelper;
use Siam\Plugs\controller\Plugs;

class PlugsInitialization
{
    /**
     * 初始化 提供插件管理的几个api
     * @api /api/plugs/get_list
     * @api /api/plugs/install
     * @api /api/plugs/update
     * @api /api/plugs/remove
     */
    public static function init()
    {
        PlugsHelper::getInstance()->addGetRouter([
            '/api/plugs/get_list' => [new Plugs, 'get_list'],
            '/api/plugs/install'  => [new Plugs, 'install'],
            '/api/plugs/update'   => [new Plugs, 'update'],
        ]);
        PlugsHelper::getInstance()->mirateView("siam/plugs","list.html");
    }
}