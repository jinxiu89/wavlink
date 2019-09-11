<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 */
namespace app\common\model;

class Language extends BaseModel
{
    protected $table = 'language';//使用数据库里这个language表

    //检测管理员 管理哪个语言的网站
    public static function getLanguageByIDs($ids) {
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
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getLanguageCodeOrID($value){
        if (isPositiveInteger($value)){
            return $value;
        }else{
            $language = self::getIDStatusByCode($value);
            return $language['id'];
        }
    }

    /**
     * 根据语言code 获取 语言状态,语言id,
     * @param 模块名|string $code 模块名
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getIDStatusByCode($code = 'en_us') {
        $map = [
            'code' => $code
        ];
        $result = self::where($map)->field('id,status,remark')->find();
        return $result;
    }

    //根据语言id获取语言code
    public static function getCodeById($id = '') {
        $map = [
            'status' => 1,
            'id' => $id
        ];
        $result = self::where($map)->find();
        return $result['code'];


    }


    public function getLanguageByLanguageId($language_id) {
        $data = [
            'status' => 1,
            'id' => $language_id
        ];
        return $this->where($data)->select();
    }
}