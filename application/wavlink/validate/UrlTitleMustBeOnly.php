<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2018/4/18
 * Time: 15:14
 */
namespace app\wavlink\validate;


class UrlTitleMustBeOnly extends BaseValidate
{
    protected $rule = [
        'url_title' => 'require|alphaDash|max:128|urlTitleIsOnly'
    ];
    protected $message = [
      'url_title' => 'url标题必须唯一，且只能是字母、数字和下划线_及破折号-及英文句号.,并且不要太长'
    ];
}