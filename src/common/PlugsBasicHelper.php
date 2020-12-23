<?php


namespace Siam\Plugs\common;

use EasySwoole\Component\Singleton;
use Siam\Plugs\model\PlugsInstalledModel;
use Siam\Plugs\service\PlugsAuthService;

class PlugsBasicHelper
{
    use Singleton;

    /**
     * 是否包含某个插件，包含则返回安装信息
     * @param string $plugsName 如果同个包目录下有多个插件，从包的路径开始 例子：ChrisPlugs/Plugs1 ，ChrisPlugs/Plugs2
     * @return array|false 返回安装信息
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function hasPlugs(string $plugsName)
    {
        //插件包是否存在。
        $has = PlugsAuthService::isPlugs($plugsName);
        if(!$has){
            return false;
        };
        //插件存在，检查是否安装。
        $plugs = PlugsInstalledModel::create()->getByPlugsName($plugsName);
        if (empty($plugs)){
            return false;
        };
        //返回安装信息。
        return [
            'plugs_name'=>$plugs->plugs_name,
            'install_version'=>$plugs->plugs_version
        ];
    }

    /**
     * 获取插件配置
     * @param $plugsName
     * @return mixed|null
     */
    public function getConfig($plugsName)
    {
        return PlugsAuthService::getPlugsConfig($plugsName);
    }
}