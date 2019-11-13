<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */

namespace app\common\model;

use PDOStatement;
use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use app\common\model\About;
use app\common\model\Product;

class Language extends BaseModel
{
    protected $table = 'language';//使用数据库里这个language表

    /**
     * @param $id
     * @return bool|string
     */
    public function checkData($id)
    {
        try {
            $information = About::getAbouts($id);
            if (empty($information)) {
                return true;
            }
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
        return "不能执行该操作，语言已经被证实已使用";
    }

    /**
     * @param $map
     * @param $order
     * @return mixed
     * @throws DbException
     */
    public static function getDataByLanguage($map, $order)
    {
        $query = self::where($map);
        $result['data'] = $query->order($order)->paginate();
        $result['count'] = $query->count();
        return $result;
    }

    //检测管理员 管理哪个语言的网站
    public static function getLanguageByIDs($ids)
    {
        $map = [
            'id' => ['in', $ids],
            'status' => 1
        ];
        $result = self::where($map)->select();
        return $result;
    }

    /***
     * 判断传入的语言 如果是模块名 就转换成id 如果是id 就直接输出id
     * @param $value
     * @return mixed
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function getLanguageCodeOrID($value)
    {
        if (isPositiveInteger($value)) {
            return $value;
        } else {
            $language = self::getIDStatusByCode($value);
            return $language['id'];
        }
    }

    /**
     * 根据语言code 获取 语言状态,语言id,
     * @param 模块名|string $code 模块名
     * @return array|false|PDOStatement|string|\think\Model
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public static function getIDStatusByCode($code = 'en_us')
    {
        $map = [
            'code' => $code
        ];
        $result = self::where($map)->field('id,status,remark')->find();
        return $result;
    }

    //根据语言id获取语言code
    public static function getCodeById($id = '')
    {
        $map = [
            'status' => 1,
            'id' => $id
        ];
        $result = self::where($map)->find();
        return $result['code'];


    }

    /***
     * @param $language_id
     * @return array
     * @throws Exception
     *
     */
    public function getLanguageByLanguageId($language_id)
    {
        $data = [
            'status' => 1,
            'id' => self::getLanguageCodeOrID($language_id)
        ];
        try {
            $data=Collection::make(self::where($data)->select())->toArray();
            return $data;
        } catch (Exception $e) {
            //
        } catch (DbException $e) {
        }
    }
}