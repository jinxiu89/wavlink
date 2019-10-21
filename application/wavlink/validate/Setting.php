<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *系统管理之系统设置 站点配置验证规则。
 */

namespace app\wavlink\validate;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

class Setting extends BaseValidate
{
    /**验证规则**/
    protected $rule = [
        'id' => 'number',
        'title' => 'require|max:200',
        'keywords' => 'require|max:200',
        'description' => 'require|max:512',
        'information' => 'require|max:255',
        'language_id' => 'require|number|languageIDBeOnly',
        'status' => 'number|in:-1,0,1',
    ];

    protected $message = [
        'id' => 'ID不合法',
        'title.require' => '标题不能为空',
        'title.max' => '标题最长不能超过200个字符',
        'keywords.require' => '关键词不能为空',
        'keywords.max' => '关键词不能超过200个字符',
        'description.require' => '描述不能为空',
        'description.max' => '描述不能超过512个字符',
        'information.require' => '底部版权信息不能为空',
        'information.max' => '底部版权信息不能超过255个字符',
        'language_id.require' => '语言ID不存在',
        'language_id.number' => '语言ID不合法',
        'language_id.languageIDBeOnly' => '语言ID必须唯一',
        'status.number' => '状态值不合法',
        'status.in' => '状态值不在合法范围内'
    ];

    protected $scene = [
        'save' => ['id','title', 'keywords', 'description', 'information', 'language_id', 'status'],
    ];

    /**
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * @return bool
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    protected function languageIDBeOnly($value, $rule = '', $data = '', $field = '')
    {
        $map = [
            'language_id' => $value,
            'status' => 1,
        ];
        if (empty($data['id'])) {
            $result = model('Setting')->where($map)
                ->field('language_id')
                ->find();
        } else {
            $result = model('Setting')->where('id', 'neq', $data['id'])
                ->where($map)
                ->field('language_id')
                ->find();
        }
        if ($result) {
            return false;
        } else {
            return true;
        }
    }
}
