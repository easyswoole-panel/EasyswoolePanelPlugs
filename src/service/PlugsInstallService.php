<?php


namespace Siam\Plugs\service;


use EasySwoole\Utility\File;
use Siam\Plugs\model\PlugsInstalledModel;

class PlugsInstallService
{
    /**
     * 插件是否已经安装
     * @param $plugsName
     * @return bool
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public static function isInstalled($plugsName)
    {
        $installedModel = PlugsInstalledModel::create()->get([
            'plugs_name' => $plugsName
        ]);
        return !!$installedModel ?? false;
    }

    public static function install($plugsName, $newInstall = false)
    {
        // 是否全新安装，是则需要遍历第一个版本到最后一个版本到安装
        if (!$newInstall){
            $model = PlugsInstalledModel::create()->getByPlugsName($plugsName);
            $installFileList = static::getInstallFIle($plugsName, (string) $model->plugs_version);
        }else{
            $installFileList = static::getInstallFIle($plugsName);
        }
        
        foreach ($installFileList as $installFile){
            // run install
            require $installFile;
        }
    }

    /**
     * 获取安装文件列表
     * @param $plugsName
     * @param null $startVersion
     * @param null $endVersion
     * @return array
     */
    public static function getInstallFIle($plugsName, $startVersion = null, $endVersion = null)
    {
        $return = static::getPlugsVersionList($plugsName);
        $installFilePath = PlugsAuthService::plugsPath($plugsName)."/src/database/";

        $finalReturn = [];
        // 根据start和end 筛选
        $start = false;
        if ($startVersion===null) $start = true;
        foreach ($return as $version){
            if (!$start){
                if ($startVersion == $version){
                    $start = true;
                }
                continue;
            }
            $finalReturn[] = $installFilePath."install_".$version.".php";
        }

        return $finalReturn;
    }

    /**
     * 获取版本列表
     * @param $plugsName
     * @return array
     */
    public static function getPlugsVersionList($plugsName)
    {
        $config = PlugsAuthService::getPlugsConfig($plugsName);
        $namespace = $config['namespace'];
        $version   = $config['version'];
        // 获取到起点和终点版本到文件列表
        $installFilePath = PlugsAuthService::plugsPath($plugsName)."/src/database/";
        $allInstallFile = File::scanDirectory($installFilePath);
        $allInstallFile = $allInstallFile["files"];
        $return = [];
        foreach ($allInstallFile as $oneFile){
            $str = str_replace($installFilePath."install_", "", $oneFile);
            $str = str_replace(".php", "", $str);
            $return[] = $str;
        }
        // 排序
        asort($return , SORT_STRING | SORT_FLAG_CASE | SORT_NATURAL);
        return $return;
    }

    /**
     * 获取最后一个版本
     * @param $plugsName
     * @return string|string[]
     */
    public static function getLastVersion($plugsName)
    {
        $versionList = static::getPlugsVersionList($plugsName);
        $end = end($versionList);
        return $end;
    }
}