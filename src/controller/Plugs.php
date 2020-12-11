<?php
/**
 * 插件市场 控制器
 * User: Siam
 * Date: 2020/12/2 0002
 * Time: 20:58
 */

namespace Siam\Plugs\controller;


use Siam\Plugs\model\PlugsInstalledModel;
use Siam\Plugs\service\PlugsAuthService;
use Siam\Plugs\service\PlugsInstallService;
use EasySwoole\Http\AbstractInterface\Controller;

class Plugs extends BasePlugsController
{
    /**
     * 扫描composer.json读取插件列表、是否已经安装
     */
    public function get_list()
    {
        $page = $this->request()->getRequestParam('page') ?? 1;
        $limit = $this->request()->getRequestParam('limit') ?? 10;

        $return = PlugsAuthService::getAllPlugs();

        // 分页支持
        $return = [
            'total' => count($return),
            'list'  => array_slice($return, ($page - 1) * $limit, $limit)
        ];

        return $this->writeJson(200, $return);
    }

    /**
     * 通过插件名 扫描运行包内的install逻辑
     */
    public function install()
    {
        $vendorName = $this->request()->getRequestParam("plugs_name");
        if (!PlugsAuthService::isPlugs($vendorName)){
            return $this->writeJson('500', [], '不是合法插件');
        }

        $config    = PlugsAuthService::getPlugsConfig($vendorName);
        $namespace = $config['namespace'];
        $version   = $config['version'];
        // 对比数据库是否安装过 版本号
        $installedInfo = PlugsInstalledModel::create()->getByPlugsName($vendorName);
        if (!!$installedInfo){
            return $this->writeJson('500', [], '已经安装过');
        }

        try{
            // 运行database脚本
            $res = PlugsInstallService::install($vendorName, true);
            if (!$res) {
                return $this->writeJson("500", [], "安装失败");
            }
            $lastVersion = PlugsInstallService::getLastVersion($vendorName);
        }catch (\Throwable $e){
            return $this->writeJson('500', [], $e->getMessage());
        }
        PlugsInstalledModel::create()->updateVersionByName($vendorName, $lastVersion);

        return $this->writeJson('200', [], '安装成功');
    }

    public function update()
    {
        $vendorName = $this->request()->getRequestParam("plugs_name");
        if (!PlugsAuthService::isPlugs($vendorName)){
            return $this->writeJson('500', [], '不是合法插件');
        }

        // 根据旧版本 调用解析逻辑 得到需要执行到脚本 依次执行
        try{
            // 运行database脚本
            PlugsInstallService::install($vendorName);
            $lastVersion = PlugsInstallService::getLastVersion($vendorName);
        }catch (\Throwable $e){
            return $this->writeJson('500', [], $e->getMessage());
        }
        PlugsInstalledModel::create()->updateVersionByName($vendorName, $lastVersion);


        return $this->writeJson('200', [], '更新成功');
    }

}