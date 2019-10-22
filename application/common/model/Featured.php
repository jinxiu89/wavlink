<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/9/8
 * Time: 15:00
 * 推荐位管理
 */

namespace app\common\model;

use think\Exception;
use think\exception\DbException;

Class Featured extends BaseModel
{
    protected $table = 'featured';//使用category表

    public function add($data)
    {
        $data['status'] = 1;
        return $this->save($data);
    }

    public function ByAll()
    {
        $order = [
            'id' => 'desc',
            'status' => 'desc',
        ];
        $featured = self::order($order);
        try {
            $result['data'] = $featured->paginate();
        } catch (DbException $e) {
            new Exception($e->getMessage());
        }
        $result['count'] = $featured->count();
        return $result;
    }
}
