<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;
use app\common\model\Language as LanguageModel;
class About extends BaseModel
{
    protected $table = "about";
    public function getAbouts($code = "en_us")
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $data = [
            'status' => 1,
            'language_id' => $language_id,
        ];

        return $this->where($data)->field('id,url_title,name')->select();

    }
}
