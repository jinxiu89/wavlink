<?php
/**
 * Created by PhpStorm.
 * User: guo
 * Date: 2017/8/28
 * Time: 10:00
 *系统管理之系统设置 站点配置验证规则。
 */
namespace app\wavlink\validate;

class Setting extends BaseValidate
{
    /**验证规则**/
    protected $rule = [
        ['id', 'number', 'id不合法'],
        ['title', 'require|max:255', '网站标题不能为空|网站标题不能太长'],
        ['keywords', 'require|max:255', '关键词不能为空|关键词过长'],
        ['description', 'require|max:1024', '描述不能为空|描述过长'],
        ['information', 'require|max:255', '底部授权信息不能为空|底部授权信息过长'],
        ['language_id', 'require|number|languageIDBeOnly', '请选择语言|语言不能为空|已有该语言的网站配置'],
        ['status', 'number|in:-1,0,1', '状态必须是数字|状态范围不合法'],
    ];


    protected function languageIDBeOnly($value, $rule = '', $data = '', $field = '') {
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
