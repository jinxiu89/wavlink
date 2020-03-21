<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/1/20
 * Time: 16:04
 */

namespace app\wavlink\validate;

/**
 * Class AuthGroup
 * @package app\wavlink\validate
 */
class AuthGroup extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'title'=>'require|max:64',
        'description'=>'max:255',
        'rules'=>'require|checkArray',
        'status' => 'integer|in:-1,0,1',
    ];
    protected $message = [
        'id' => 'ID不合法（为空和不为数字）',
        'title' => '组名必须不为空或长度不能够大于64个字符',
        'description' => '描述不能够大于255个字符',
        'rules'=>'规则还是需要选滴',
        'status' => '状态值不合法（整型，-1,0,1）'
    ];
    protected $scene = [
        'add'=>['title','description','rules'],
        'edit'=>['id','title','description','rules'],
        'changeStatus' => ['id', 'status']
    ];

    /**
     *
     * @param array $value
     * @param string $rule
     * @param array $data
     * @return bool
     */
    protected function checkArray(array $value,$rule='',$data=[]){
        if(is_array($value)){
            return true;
        }
        return false;
    }
}