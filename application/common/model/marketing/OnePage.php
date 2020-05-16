<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 *营销单页面管理
 */

namespace app\common\model\marketing;

use app\common\model\Language as LanguageModel;
use app\common\model\BaseModel;

/**
 * Class OnePage
 * @package app\common\model\marketing
 */
Class OnePage extends BaseModel
{
    protected $table = 'marketing';

    //后台宣传单页首页面
    public function getMarketing()
    {
        $order = [
            'id' => 'desc',
            'status' => 'desc',
        ];
        return $this->order($order)->paginate();
    }

    public function getMarketingByCode($code)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $data = [
            'status' => 1,
            'language_id' => $language_id,
        ];
        return $this->where($data)->find();
    }

    public function getDetailsByName($name)
    {
        return $this->where(array('name' => $name))->find();
    }
}