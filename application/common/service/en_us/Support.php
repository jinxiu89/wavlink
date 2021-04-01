<?php
/**
 * @Create by PhpStorm
 * @author:jinxiu89@163.com
 * @Create Date:2021/3/29 10:32
 * @User: kevin
 * @Current File : Support.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\common\service\en_us;

use app\common\model\Service\Drivers;
use app\common\model\Service\Firmware;
use app\common\model\Service\Manual;
use think\Exception;
use think\facade\Cache;

/**
 * Class Support
 * @package app\common\service\en_us
 */
class Support extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $language_id
     * @param $model
     * 产品的标准型号
     * @return mixed
     */
    public function getDriverByModel($language_id, $model)
    {
        try {
            if ($this->debug == false) {
                $data = Cache::get(__FUNCTION__ . $language_id . $model);
                if ($data) return $data;
                $obj = Drivers::where('name','like','%'.$model.'%')->where(['language_id'=>$language_id])->select();
                if(!$obj->isEmpty()) {
                    Cache::set(__FUNCTION__ . $language_id . $obj->toArray());
                    return $obj->toArray();
                }
                return [];
            }
            $obj=Drivers::where('name','like','%'.$model.'%')->where(['language_id'=>$language_id])->select();
            if(!$obj->isEmpty()) return $obj->toArray();
            return [];
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * @param $language_id
     * @param $model
     * @return mixed
     */
    public function getManualByModel($language_id, $model): array
    {
        try {
            if ($this->debug == false) {
                $data = Cache::get(__FUNCTION__ . $language_id . $model);
                if ($data) return $data;
                $obj = Manual::where('model','like','%'.$model.'%')->where(['language_id'=>$language_id])->with('downloads')->select();
                if(!$obj->isEmpty()){
                    Cache::set(__FUNCTION__ . $language_id . $model, $obj->toArray());
                    return $obj->toArray();
                }
                return [];
            }
            $obj=Manual::where('model','like','%'.$model.'%')->where(['language_id'=>$language_id])->with('downloads')->select();
            if(!$obj->isEmpty()) return $obj->toArray();
            return [];
        } catch (\Exception $exception) {
            new throwException('服务器内部错误:' . $exception->getMessage());
        }
    }

    /**
     * @param $language_id
     * @param $model
     * @return array
     */
    public function getFirmwareByModel($language_id,$model):array{
        try{
            if ($this->debug == false) {
                $data = Cache::get(__FUNCTION__ . $language_id . $model);
                if ($data) return $data;
                $obj = Firmware::where('model','like','%'.$model.'%')->where(['language_id'=>$language_id])->select();
                if(!$obj->isEmpty()){
                    Cache::set(__FUNCTION__ . $language_id . $model, $obj->toArray());
                    return $obj->toArray();
                }
                return [];
            }
            $obj=Firmware::where('model','like','%'.$model.'%')->where(['language_id'=>$language_id])->select();
            if(!$obj->isEmpty()) return $obj->toArray();
            return [];
        }catch (Exception $exception){
            return [];
        }
    }
}