<?php
/**
 * 插件认证逻辑
 * User: Siam
 * Date: 2020/12/1 0001
 * Time: 21:20
 */

namespace Siam\Plugs\service;


use Siam\Plugs\model\PlugsInstalledModel;

class PlugsAuthService
{
    const configName = "esPlugsConfig.php";
    public static function plugsPath($vendorName)
    {
        if (is_file(EASYSWOOLE_ROOT."/Addons/{$vendorName}/".static::configName)){
            return EASYSWOOLE_ROOT."/Addons/{$vendorName}/";
        }
        return EASYSWOOLE_ROOT."/vendor/".$vendorName."/";
    }

    /**
     * 是否为插件
     * @param $vendorName
     * @return bool
     */
    public static function isPlugs($vendorName, $containAddons = false)
    {
        $vendorPath = static::plugsPath($vendorName);
        if (is_file($vendorPath.static::configName)){
            return true;
        }

        if ($containAddons){
            if (is_file(EASYSWOOLE_ROOT."/Addons/{$vendorName}/".static::configName)){
                return true;
            }
        }
        return false;
    }

    /**
     * 获取插件配置
     * @param $vendorName
     * @return mixed|null
     */
    public static function getPlugsConfig($vendorName)
    {
        $vendorPath = static::plugsPath($vendorName);

        if (is_file($vendorPath.static::configName)){
            $fullPath =  $vendorPath.static::configName;
            $temp = require $fullPath;
            $temp['plugs_path'] = $vendorPath;
            return $temp;
        }

        return null;
    }

    /**
     * 获取插件列表
     * @param boolean $installed
     */
    public static function getAllPlugs($installed = false)
    {
        $composerFile = file_get_contents(EASYSWOOLE_ROOT."/composer.json");
        $composerFile = json_decode($composerFile, TRUE);
        $vendorList   = $composerFile['require'];

        $return = [];
        foreach ($vendorList as $vendorName  => $vendorVersion){
            if (PlugsAuthService::isPlugs($vendorName)){
                $temp = PlugsAuthService::getPlugsConfig($vendorName);
                $info = PlugsInstalledModel::create()->getByPlugsName($vendorName);
                if ($installed && !!$info){
                    $temp['installed'] = !!$info;
                    $temp['installed_version'] = !!$info ? $info->plugs_version : null;
                    $temp['plugs_name'] = $vendorName;

                    $return[] = $temp;
                }else{
                    $temp['installed'] = !!$info;
                    $temp['installed_version'] = !!$info ? $info->plugs_version : null;
                    $temp['plugs_name'] = $vendorName;

                    $return[] = $temp;
                }
            }
        }
        // addons dir
        if (!is_file(EASYSWOOLE_ROOT."/Addons/packlist.php")) {
            return $return;
        }
        $addonsFile = require EASYSWOOLE_ROOT."/Addons/packlist.php";
        foreach ($addonsFile as $vendorName){
            if (PlugsAuthService::isPlugs($vendorName, true)){
                $temp = PlugsAuthService::getPlugsConfig($vendorName);
                $info = PlugsInstalledModel::create()->getByPlugsName($vendorName);
                if ($installed && !!$info){
                    $temp['installed'] = !!$info;
                    $temp['installed_version'] = !!$info ? $info->plugs_version : null;
                    $temp['plugs_name'] = $vendorName;

                    $return[] = $temp;
                }else{
                    $temp['installed'] = !!$info;
                    $temp['installed_version'] = !!$info ? $info->plugs_version : null;
                    $temp['plugs_name'] = $vendorName;

                    $return[] = $temp;
                }
            }
        }

        return $return;
    }
}