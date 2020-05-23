<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/5/20 14:51
 * @User: admin
 * @Current File : DriversCategory.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\model\Service;


use app\common\model\BaseModel;

/**
 * Class DriversCategory
 * @package app\common\model\Service
 */
class DriversCategory extends BaseModel
{
    protected $table='tb_drivers_category';

    /**
     * @param string $status
     * @param string|null $language_id
     * @return mixed|\think\Paginator
     */
    public function getDataByLanguageId($status,$language_id){
        if (empty($status)) return self::where(['language_id'=>$language_id])->order(['level','id']);
        return self::where(['status'=>1,'language_id'=>$language_id])->order(['level','id']);
    }


    /**
     * @param $data
     * @return bool
     */
    public function saveData($data){
        try{
            return self::allowField(true)->save($data,['id'=>$data['id']]);
        }catch (\Exception $exception){
            return false;
        }
    }
}