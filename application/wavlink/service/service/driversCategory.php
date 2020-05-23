<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2020/5/20 15:59
 * @User: admin
 * @Current File : driversCategory.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\service\service;
use app\common\model\Service\DriversCategory as model;
use app\wavlink\service\Base;

/**
 * Class driversCategory
 * @package app\wavlink\service\service
 */
class driversCategory extends Base
{
    public function __construct()
    {
        $this->model = new model();
    }

    /**
     * @param $parent_id
     */
    public function getParent($parent_id){
        try{
            return $this->model->field('id,parent_id,level,path')->where(['id'=>$parent_id])->find();
        }catch (\Exception $exception){
            //todo:: 异常
        }
    }
    public function getCategoryID($category,$language){
        try{
            return $this->model->getCategoryID($category,$language);
        }catch (\Exception $exception){
            return 'cuowu';
        }
    }
    /**
     * @param string $status
     * @param $language_id
     * @return array
     */
    public function getDataByLanguageId($status, $language_id)
    {
        try {
            $response = $this->model->getDataByLanguageId($status, $language_id);
            $result['count'] = $response->count();
            $result['data'] = $response->paginate(25);
            return $result;
        } catch (\Exception $exception) {
            return [];
        }
    }
}