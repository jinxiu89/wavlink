<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/17
 * Time: 15:34
 */

namespace app\wavlink\validate;


class Faq extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'name' => 'require|max:255|unique:faq,name',
        'category_id' => 'require|number|gt:0',
        'language_id' => 'require|number|gt:0',
        'problem' => 'require',
        'answer' => 'require',
        'status' => 'integer|in:-1,0,1'
    ];


    protected $message = [
        'id' => 'ID不合法！',
        'name' => '名称必须填写,换个问题名称试试？',
        'category_id' => '所属分类必选择',
        'language_id' => '语言别乱改',
        'problem' => '问题呢？',
        'answer' => '答案呢？',
        'status' => '状态值不合法'
    ];
    protected $scene = [
        'add' => ['name', 'category_id', 'language_id', 'problem', 'answer'],
        'edit' => ['id', 'name', 'category_id', 'language_id', 'problem', 'answer'],
        'changeStatus' => ['id', 'status']
    ];
}