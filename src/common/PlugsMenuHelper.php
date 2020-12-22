<?php


namespace Siam\Plugs\common;


use App\Model\AuthModel;
use App\Model\SystemModel;
use EasySwoole\Component\Singleton;

class PlugsMenuHelper
{

    use Singleton;

    /**
     * 新增菜单 参照panel主库 新增权限的逻辑
     * @param $menuName
     * @param $menuPath
     * @param $menuIcon
     */
    public function add($menuName, $menuPath, $menuIcon)
    {

        $data  = [
            'auth_name'   => $menuName,
            'auth_rules'  => $menuPath ?? '0',
            'auth_icon'   => $menuIcon ?? '',
            'auth_type'   => '1',
            'create_time' => time(),
            'update_time' => time(),
        ];
        $model = new AuthModel($data);
        $rs = $model->save();
        if ($rs) {
            // 更新到排序中
            $system = SystemModel::create()->get();
            $auth = json_decode($system->auth_order);
            $auth[] = ['id'=>$model->auth_id];
            $system->auth_order = json_encode($auth);
            $system->update();
            return true;
        } else {
            throw new \Exception($model->lastQueryResult()->getLastError());
        }
    }
}