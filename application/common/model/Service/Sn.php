<?php
/**
 * Created by PhpStorm.
 * User: web jinxiu89@163.com
 * Date: 2018/8/31
 * Time: 10:36
 * 命名规范：文件名首字母大写
 * 类 首写字母大写
 * 函数 驼峰命名  如getInfo
 */

namespace app\common\model\Service;
use app\common\model\BaseModel;
use app\common\model\Model as prdModel;

class Sn extends BaseModel
{
    protected $autoWriteTimestamp = 'datetime';
    protected $table = 'tb_sn';

    public function getDateByStatus()
    {
        $model = 'Sn';
        $map = [];
        $order = [
            'id' => 'asc',
        ];
        return Search($model, $map, $order);
    }

    public function models()
    {
        return $this->belongsTo('Model');
    }

    public function getDataBySn($sn = '')
    {
        if (!isset($sn) || strlen($sn) != 15) {
            return show(0, '', '', '', '', lang('The length of Serial Number is invalid.'));
        }
        //新版本切片
        $date = substr($sn, 0, 3);
        $cate = substr($sn, 3, 1);//分类可以单独处理
        $code = substr($sn, 4, 3);
        $spec = substr($sn, 7, 2);
        $status = substr($sn, 9, 1);
        $count = intval(substr($sn, 10, 5));
        // model层获取model id
        $model_id = (new prdModel())->getDataByCode($code);
        if (!$model_id) {
            return show(0, '', '', '', '', lang('Serial Number Error, the fourth o sixth number is incorrect.'));
        }
        if (!empty($this)) {
            $res = $this->getSnData($date, $model_id['id'], $spec);
            if (!$res) {
                return show(0, '', '', '', '', lang('Serial Number Error'));
            }
            $soft = $model_id->soft;
            $ver = [];
            foreach ($soft as $item) {
                $ver[] = $item;
            }
            $res->models;
            $result = $res->toArray();
            $result['cate'] = (new Cate())->getDescriptionByCode($cate);
            $result['ver'] = $ver;
            if (strcmp($status, 'N') == 0) {//正常SN
                if ($count > 0 and $count <= $result['count']) {
                    return toJson($result);
                } else {
                    return show(0, '', '', '', '', lang('Number Segment Error'));
                }
            } else if (strcmp($status, 'Q') == 0) {//返修比例SN
                if ($count > 0 and $count <= $result['count']) {
                    return toJson($result);
                } else {
                    return show(0, '', '', '', '', lang('Number Segment Error'));
                }
            }
        }
    }

    /**
     * @param $date
     * @param $model_id
     * @param $spec
     * @return array|false|\PDOStatement|string|\think\Collection|void
     */
    public function getSnData($date, $model_id, $spec)
    {
        $map = array(
            'prefix' => $date,
            'spec' => $spec,
            'model_id' => $model_id,
        );
        try {
            $data = $this->where($map)->find();
            return $data;
        } catch (\Exception $e) {
            return false;
        }
    }
}