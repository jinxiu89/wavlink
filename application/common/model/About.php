<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;
use app\common\model\Language as LanguageModel;
use PDOStatement;
use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class About extends BaseModel
{
    protected $table = "about";

    /**
     * @param string $code
     * @return array|PDOStatement|string|Collection
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function getAbouts($code = "en_us")
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $query=self::where(['language_id' => $language_id]);
        $data['data']=$query->field('id,url_title,name,keywords,language_id,listorder,create_time,status')->paginate();
        $data['count']=$query->count();
        return $data;
    }

    /**
     * @param $url_title
     * @param $code
     * @return array|PDOStatement|string|\think\Model|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getArticleByUrlTitle($url_title,$code){
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $map = [
            'status' => 1,
            'language_id' => $language_id,
            'url_title' => $url_title
        ];
        return self::where($map)->find();
    }
}
