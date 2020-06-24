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
use think\facade\Cache;
use think\facade\Log;

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
     * @param $language_id
     * @return Collection
     */
    public function getLastNew($language_id)
    {
        try {
            if (false == $this->debug) {
                $data = Cache::get($language_id . __FUNCTION__);
                if ($data) return $data;
                $obj = $this->where(['status' => 1, 'language_id' => $language_id])
                    ->order(['update_time' => 'desc'])
                    ->limit(2)
                    ->field('id,title,url_title,logo,seo_description,update_time')
                    ->select();
                Cache::set($language_id . __FUNCTION__, $obj);
                return $obj;
            }
            return $this->where(['status' => 1, 'language_id' => $language_id])
                ->order(['update_time' => 'desc'])
                ->limit(2)
                ->field('id,title,url_title,logo,seo_description,update_time')
                ->select();
        } catch (\Exception $exception) {
            if (true == $this->debug) Log::error(__CLASS__ . __FUNCTION__ . ':' . $exception->getMessage());
            return;
        }
    }

    /**
     * @param $url_title
     * @param $code
     * @return array|PDOStatement|string|\think\Model|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function getDetailsByUrlTitle($url_title, $code)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $map = [
            'status' => 1,
//            'language_id' => $language_id,
            'url_title' => $url_title
        ];
        return self::where($map)->find();
    }
}