<?php


namespace app\common\model;

use think\exception\DbException;

/**
 * Class Firmware
 * @package app\common\model
 */
class Firmware extends BaseModel
{
    protected $table = 'tb_firmware';

    /**
     * @param $language_id
     * @param int $status
     * @return mixed
     * @throws DbException
     */
    public function getDataByLanguageId($language_id, $status = 1)
    {
        $query = $this->where(['language_id' => $language_id, 'status' => $status]);
        $result['data'] = $query->order(['listorder' => 'desc', 'create_time' => 'desc'])->field('id,title,language_id,name,model,description,size,create_time,status,listorder')->paginate();
        $result['count'] = $query->count();
        return $result;
    }

    /**
     * @param $title
     * @return array|\PDOStatement|string|\think\Model|null
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getDataByTitle($title)
    {
        return $data = $this->where(['title' => $title])->find();
    }
}