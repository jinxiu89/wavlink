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
        $result['data'] = $query->order(['listorder'=>'desc','create_time'=>'desc'])->field('id,language_id,name,model,description,size,create_time,status,listorder')->paginate();
        $result['count'] = $query->count();
        return $result;
    }
}