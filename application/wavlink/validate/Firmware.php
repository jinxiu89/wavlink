<?php


namespace app\wavlink\validate;

/**
 * Class Firmware
 * @package app\wavlink\validate
 */
class Firmware extends BaseValidate
{
    protected $rule = [
        'id' => 'require|integer',
        'language_id' => 'require|integer',
        'name' => 'require|max:64',
        'size' => 'require|max:32',
        'url' => 'require',
        'description' => 'require|max:1024',
        'status' => 'require|integer|in:-1,0,1',
    ];
    protected $message = [
        'id' => 'ID不合法',
        'language_id' => '语言ID不合法',
        'name.require' => '驱动名称不能为空',
        'name.max' => '驱动名称不能太长',
        'size.require' => '大小不能为空',
        'size.max' => '大小不能太长',
        'url' => 'url不能为空，需要是正确的资源地址，以免毁了公司信誉',
        'description' => '描述本驱动使用的机型和注意事项',
        'status' => '状态值不合法',
    ];
    protected $scene = [
        'add' => ['language_id', 'name', 'size', 'url', 'description'],
        'edit' => ['id', 'language_id', 'name', 'size', 'url', 'description'],
        'changeStatus' => ['id', 'status'],
        'id'=>['id']
    ];
}