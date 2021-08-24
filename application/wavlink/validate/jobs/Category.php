<?php

declare(strict_types=1);
/**
 * @Create by vscode,
 * @author:jinxiu89@163.com
 * @Create Date:2021年08月24日 11:17:48 星期二
 * @User: admin
 * @Current File : Category.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\validate\jobs;

use app\wavlink\validate\BaseValidate;

class Category extends BaseValidate
{
    # todo:分类验证
    /**验证规则**/
    protected $rule = [
        'id' => 'number',
        'name' => 'require|unique:about,name',
        'title' => 'require',
    ];
    protected $message = [
        'id' => 'ID不合法',
        'name.require' => '标题名称不能为空',
        'name.unique' => '标题名称不能重复',
        'title.require' => 'title不能为空',
    ];
    /**场景设置**/
    protected $scene = [
        'add' => ['name', 'title'],
        'edit' => ['name', 'title'],
        // 'changeStatus' => ['id', 'status'],
    ];
}