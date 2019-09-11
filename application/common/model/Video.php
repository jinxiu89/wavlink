<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;

use app\common\model\Language as LanguageModel;
Class Video extends BaseModel
{
    protected $table = 'video';

    public function getVideoByLanguage($code) {
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        return self::getDataByStatusLanguage(1, $order, $code);
    }

    //获取子分类的视频列表
    public function getVideosByCategoryId($code, $id) {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $data = [
            'status' => 1,
            'language_id' => $language_id,
            'category_id' => $id,
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        return Search('Video',$data,$order);
    }

}