<?php

namespace Siam\Plugs\model;

use EasySwoole\ORM\AbstractModel;

/**
 * SiamPlugsInstalledModel
 * Class SiamPlugsInstalledModel
 * Create With ClassGeneration
 * @property int $id //
 * @property string $plugs_name // 插件包名
 * @property string $plugs_version // 插件包版本号
 * @property mixed $create_time // 安装时间
 */
class PlugsInstalledModel extends BaseModel
{
	protected $tableName = 'plugs_installed';
	protected $autoTimeStamp = 'datetime';
	protected $updateTime = false;

	public function getList(int $page = 1, int $pageSize = 10, string $field = '*'): array
	{
		$list = $this
		    ->withTotalCount()
			->order($this->schemaInfo()->getPkFiledName(), 'DESC')
		    ->field($field)
		    ->page($page, $pageSize)
		    ->all();
		$total = $this->lastQueryResult()->getTotalCount();
		$data = [
		    'page'=>$page,
		    'pageSize'=>$pageSize,
		    'list'=>$list,
		    'total'=>$total,
		    'pageCount'=>ceil($total / $pageSize)
		];
		return $data;
	}

    /**
     * 根据插件名获取Model
     * @param $plugsName
     * @return array|bool|AbstractModel|\EasySwoole\ORM\Db\Cursor|\EasySwoole\ORM\Db\CursorInterface|PlugsInstalledModel|null
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
	public function getByPlugsName($plugsName)
    {
        return static::create()->get([
            'plugs_name' => $plugsName
        ]);
    }

    /**
     * 更新插件安装版本号
     * @param $plugsName
     * @param $version
     * @return bool|int
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public function updateVersionByName($plugsName, $version)
    {
	    $info = static::getByPlugsName($plugsName);
	    if (!$info) {
	        $info = new static();
	        $info->plugs_name = $plugsName;
            $info->plugs_version = $version;
            $info->save();
        }else{
            $info->plugs_version = $version;
            return $info->update();
        }
    }
}

