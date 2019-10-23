<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/5/25
 * Time: 16:46
 */

namespace app\wavlink\validate;
/**
 * Class ListorderValidate
 * @package app\wavlink\validate
 */
class ListorderValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|integer',
        'listorder' => 'require|integer',
    ];
    protected $message = [
        'id.require' => 'ID不合法！',
        'id.integer' => 'ID必须为整形！',
        'listorder.require' => '排序字段不合法',
        'listorder.integer' => '排序字段必须为整形',
    ];
    protected $scene = [
        'listorder' => ['id', 'listorder']
    ];
}