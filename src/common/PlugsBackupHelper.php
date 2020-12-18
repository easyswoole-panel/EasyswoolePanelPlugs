<?php
/**
 * 插件 备份文件助手
 */

namespace Siam\Plugs\common;


use EasySwoole\Component\Singleton;
use EasySwoole\Utility\File;
class PlugsBackupHelper
{
    use Singleton;

    /**
     * 创建备份目录和文件
     * @param string $vendorName 目录名
     * @param string $backupFileName 文件名
     * @return array 路径和文件名数组
     * @throws \Exception
     */
    public function create(string $vendorName, string $backupFileName) : array
    {
        //检查相关文件夹
        $backupPath = EASYSWOOLE_ROOT.'/backup/'.$vendorName;
        $filePath =  $backupPath.'/'.$backupFileName;

        if(!file_exists($backupPath)){
            //初始化创建
            $createDir = File::createDirectory($backupPath);
            if(!$createDir){
                throw new \Exception("文件夹{$backupPath}创建失败");
            }
        }
        //文件
        $createFile = File::touchFile($filePath);
        if(!$createFile){
            throw new \Exception("文件{$backupFileName}创建失败");
        }

        $pathInfo['dirPath'] = $backupPath;
        $pathInfo['fileName'] = $backupFileName;

        return $pathInfo;

    }

    /**
     * 删除文件夹
     * @param $backupFilePath
     * @return bool
     */
    public function delete($backupFilePath)
    {
       return File::deleteDirectory($backupFilePath);
    }
}