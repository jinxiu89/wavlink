<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/18
 * Time: 17:07
 */

namespace app\common\model\Service;

use app\common\model\BaseModel;
use app\common\model\Language as LanguageModel;
use think\Db;

/**
 * Class Manual
 * @package app\common\model
 */
class Manual extends BaseModel
{
    protected $table = "tb_manual";
    protected $autoWriteTimestamp = 'date';


    public function downloads()
    {
        return $this->hasMany('ManualDownload', 'manual_id');
    }

    public function getDataByLanguage($language_id)
    {
        $result = $this->where(['status' => 1, 'language_id' => $language_id])->paginate(6);
        return ModelsArr($result, 'model', 'modelGroup');
    }

    public function checkDownload($id)
    {
        try {
            $download = Db::table('tb_manual_download')->alias('download')->where('manual_id', '=', $id)->select();
            return !empty($download) ? false : true;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
    // 前台 获取当前选择的子分类下的驱动列表，models产品型号字段处理

    /**
     * @param $code
     * @param $categoryId
     * @param string $order
     * @return mixed|\think\Paginator
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getManualByCategoryId($code, $categoryId, $order = "desc")
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $data = [
            'status' => 1,
            'language_id' => $language_id,
        ];
        $order = [
            'update_time' => $order,
            'listorder' => 'desc',
            'id' => 'desc',
        ];
        if (empty($categoryId)) {
            $count = $this->where($data)->count();
            $result = $this->where($data)->order($order)->paginate(6, true);
        } else {
            $count = $this->where($data)->where('category_id', 'in', $categoryId)->count();
            $result = $this->where($data)->where('category_id', 'in', $categoryId)->order($order)->paginate(6, true);
        }
        return ['count' => $count, 'result' => $result];
    }

    /***
     * @param $code
     * @param $chinld
     * 当有 child 的分类 用这个方法获取到他所有的子分类下的内容
     * @param $parent
     * @param string $order
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getdataByChild($code, $chinld, $parent, $order = "desc")
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);//$code 转成 language_id
        $order = [
            'update_time' => $order,
            'listorder' => 'desc',
            'id' => 'desc',
        ];

        try {
            if (empty($chinld)) {
                //没有下一级分类的情况
                $data = $this->where([
                    'language_id' => $language_id,
                    'category_id' => $parent['id'],
                ])->order($order)->paginate(6);
            }
            if (!empty($chinld)) {
                foreach ($chinld as $vo) {
                    $result = $this->where([
                        'language_id' => $language_id,
                        'category_id' => $vo['id'],
                    ])->order($order)->paginate(6);
                    $res[] = $result;//TurnArray就返回的是一个二维数组 ，在压到$data就变成了三维数组了
                }
                foreach ($res as $vo) {
                    foreach ($vo as $io) {
                        $data[] = $io;
                    }
                }
                $parentData = $this->where([
                    'language_id' => $language_id,
                    'category_id' => $parent['id']
                ])->order($order)->paginate(6);
                $data = array_merge($data, $parentData);
            }
            return $data;
        } catch (\Exception $e) {
            //抛出异常
        }
    }

    public function getSelectManual($code, $key)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);
        $model = 'Manual';
        $map['status'] = 1;
        $map['title|model|url_title|keywords|description'] = array('like', '%' . $key . '%');
        $map['language_id'] = $language_id;
        $order = [
            'id' => 'desc',
        ];
        return Search($model, $map, $order);
    }

    /**
     * @param $code
     * @param $url_title
     * @return array|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getDownloadByTitle($code, $url_title)
    {
        $language_id = LanguageModel::getLanguageCodeOrID($code);//$code 转成 language_id
        try {
            $data = $this->where([
                'language_id' => $language_id,
                'url_title' => $url_title
            ])->find();
            if ($data) {
                $result['manual'] = $data->toArray();
                $result['downloads'] = TurnArray($data->downloads);
                return $result;
            }
        } catch (\Exception $e) {
            //异常后面修好
            return $e->getMessage();
        }
    }

    /**
     * @param $category
     * @return mixed
     */
    public function getUrlTitle($category)
    {
        return getUrlByCategoryID($category);
    }
}