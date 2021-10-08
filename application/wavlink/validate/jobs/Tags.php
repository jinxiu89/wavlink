<?php

declare(strict_types=1);
/**
 * @Create by vscode,
 * @author:jinxiu89@163.com
 * @Create Date:2021年10月07日 15:13:12 星期四
 * @User: admin
 * @Current File : Tags.php
 * @格言：溪涧岂能留得住，终归大海做波涛 --李忱
 * @格言： 我的内心因看见大海而波涛汹涌
 **/

namespace app\wavlink\validate\jobs;

use app\wavlink\validate\BaseValidate;

class Tags extends BaseValidate
{
    # todo:分类验证
    /**验证规则**/
    protected $rule = [
        'id' => 'number',
        'tags' => 'require|unique:tb_resume_tags,tags',
    ];
    protected $message = [
        'id' => 'ID不合法',
        'tags.require' => '标签名称不能为空',
        'tags.unique' => '标签名称不能重复',
    ];
    /**场景设置**/
    protected $scene = [
        'add' => ['tags'],
        'edit' => ['tags', 'id'],
    ];
}