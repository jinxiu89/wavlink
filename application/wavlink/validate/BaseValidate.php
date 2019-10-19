<?php
namespace app\wavlink\validate;
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/9
 * Time: 13:36
 */

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;
use think\facade\Request;
use think\Validate;

/**
 * Class BaseValidate
 * @package app\wavlink\validate
 *
 */
class BaseValidate extends Validate
{

    /**
     * 判断url_title 唯一的
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * @return bool
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    protected function urlTitleIsOnly($value,$rule = '', $data = '', $field = ''){
        $con = Request::controller();
        $map = [
            'url_title'=>$value,
            'language_id'=>$data['language_id']
        ];
        if (empty($data['id'])){
            $result = model($con)->where($map)
                ->field('url_title')
                ->find();
        }else{
            $result = model($con)->where('id','neq',$data['id'])
                ->where($map)
                ->field('url_title')
                ->find();
        }
        if ($result){
            return false;
        }else{
            return true;
        }
    }
}