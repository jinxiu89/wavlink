<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */

namespace app\common\model\Content;

use app\common\model\Language as LanguageModel;
use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use app\common\model\BaseModel;

class Article extends BaseModel
{
    protected $table = "article";//使用article表

    public function getArticle($id = '', $language_id)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($language_id);
        if (empty($id)) {
            $data = [
                'status' => 1,
                'language_id' => $language_id
            ];
        } else {
            $data = [
                'status' => 1,
                'language_id' => $language_id,
                'category_id' => $id
            ];
        }

        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        return Search('article', $data, $order);
    }

    //前台获取数据开始，调用5篇排序最高的文章，

    /***
     * @param $code
     * @param $limit
     * @return array|PDOStatement|string|Collection
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function getArticleList($code, $limit)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $map = [
            'status' => 1,
            'language_id' => $language_id,
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        return $this->where($map)->order($order)->limit($limit)->field('title,url_title')->order('id desc')->select();
    }

    /**
     * @param $code
     * @return Collection
     * @throws Exception
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException 前台首页 新闻调用
     */
    public function getLastNew($code)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $result = $this->where(['status' => 1, 'language_id' => $language_id])->order(['update_time' => 'desc'])->limit(2)->field('id,title,url_title,logo,seo_description,update_time')->select();
        return Collection::make($result);
    }
}