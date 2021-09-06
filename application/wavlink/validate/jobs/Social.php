<?php

declare(strict_types=1);
/**
 * @Create by vscode,
 * @author:jinxiu89@163.com
 * @Create Date:2021年08月24日 17:40:58 星期二
 * @User: admin
 * @Current File : Socail.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\validate\jobs;

use app\wavlink\validate\BaseValidate;

class Social extends BaseValidate
{
    /**验证规则**/
    protected $rule = [
        'id' => 'number',
        'title' => 'require|unique:tb_social_jobs,title',
        'keywords' => 'require',
        'description' => 'require',
    ];
    protected $message = [
        'id' => 'ID不合法',
        'title.require' => '标题名称不能为空',
        'title.unique' => '标题名称不能重复',
        'keywords.require' => '关键字不能为空',
        'description.require' => '职位描述与要求不能为空',
    ];
    /**场景设置**/
    protected $scene = [
        'v' => ['title', 'keywords', 'description'],
        'edit' => ['description', 'keywords']
        // 'changeStatus' => ['id', 'status'],
    ];
}