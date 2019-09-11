<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;

use app\common\model\Language as LanguageModel;

Class Images extends BaseModel
{
    protected $table = 'images';

    // 根据状态和语言查找 所有首页产品
    public function getImages($status, $language_id) {
        $data = [
            'status' => $status,
            'language_id' => $language_id
        ];
        $order = [
            'featured_id' => 'desc',
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        return self::getDataByOrder($data, $order);
    }

    //获取推荐位的产品图片
    public function getImagesByFeatured($code = "en_us", $featured = 4) {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $data = [
            'status' => 1,
            'featured_id' => $featured,
            'language_id' => $language_id
        ];
        $order = [
            'featured_id' => 'desc',
            'listorder' => 'desc',
            'id' => 'desc',
        ];
        return Search('images', $data, $order);
    }

}